(function () {
  if (window.__IVENTO_CALENDAR_LOADED__) return;
  window.__IVENTO_CALENDAR_LOADED__ = true;

  let currentDate = new Date();

  const calendar = document.getElementById("calendar");
  const calendarTitle = document.getElementById("calendarTitle");
  const prevMonthBtn = document.getElementById("prevMonth");
  const nextMonthBtn = document.getElementById("nextMonth");
  const slotInfo = document.getElementById("slotInfo");

  // booking.php only (hidden input for selected date)
  const hiddenDateInput = document.getElementById("event_date");

  if (!calendar || !calendarTitle) return;

  // 🟠 Demo bookings
  const demoBookings = {
    "2025-10-05": { Morning: true, Afternoon: true, Evening: true }, // full
    "2025-10-12": { Morning: true, Afternoon: true, Evening: true },
    "2025-10-18": { Morning: true, Afternoon: true, Evening: true },
    "2025-10-22": { Morning: true, Afternoon: false, Evening: false }, // partial
    "2025-11-03": { Morning: true, Afternoon: true, Evening: true },
    "2025-11-10": { Morning: true, Afternoon: true, Evening: true },
    "2025-11-15": { Morning: false, Afternoon: true, Evening: false } // partial
  };

  // 🔹 Fetch from DB
  async function getBookings(month, year) {
    try {
      const res = await fetch(`fetch_bookings.php?month=${month}&year=${year}`);
      const dbData = await res.json();
      return { ...demoBookings, ...dbData }; // merge demo + DB
    } catch (err) {
      console.error("Error fetching bookings:", err);
      return demoBookings;
    }
  }

  // 🔹 Render Calendar
  async function renderCalendar() {
    calendar.innerHTML = "";

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const monthName = currentDate.toLocaleString("default", { month: "long" });

    calendarTitle.textContent = `${monthName} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    const bookings = await getBookings(month + 1, year);
    const today = new Date();

    // ✅ Reset slotInfo each month change
    if (slotInfo) slotInfo.textContent = "";

    // empty cells for alignment
    for (let i = 0; i < firstDay; i++) {
      calendar.innerHTML += `<div></div>`;
    }

    // loop through days
    for (let day = 1; day <= lastDate; day++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
      let cls = "calendar-day";
      let tooltip = "";

      if (bookings[dateStr]) {
        const slots = bookings[dateStr];
        const bookedCount = Object.values(slots).filter(v => v).length;

        if (bookedCount === 3) {
          cls += " full";
          tooltip = "Fully booked";
        } else if (bookedCount > 0) {
          cls += " partial";
          const free = Object.keys(slots).filter(s => !slots[s]).join(", ");
          const taken = Object.keys(slots).filter(s => slots[s]).join(", ");
          tooltip = `Booked: ${taken}\nAvailable: ${free}`;
        } else {
          cls += " available";
          tooltip = "All slots available";
        }
      } else {
        cls += " available";
        tooltip = "All slots available";
      }

      // mark past days
      if (new Date(year, month, day) < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
        cls += " past";
        tooltip = "Past date (not available)";
      }

      // highlight today
      if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
        cls += " today";
      }

      // 🔹 add tooltip for hover
      calendar.innerHTML += `<div class="${cls}" data-date="${dateStr}" title="${tooltip}">${day}</div>`;
    }

    // 🔹 Enable clicks only in booking.php
    if (hiddenDateInput) {
      document.querySelectorAll(".calendar-day").forEach(dayEl => {
        dayEl.addEventListener("click", () => {
          if (dayEl.classList.contains("past") || dayEl.classList.contains("full")) return;

          // remove old selection
          document.querySelectorAll(".calendar-day").forEach(d => d.classList.remove("selected"));
          dayEl.classList.add("selected");

          // save date
          hiddenDateInput.value = dayEl.dataset.date;

          // show slot info
          const slots = bookings[dayEl.dataset.date];
          if (dayEl.classList.contains("partial") && slots) {
            let info = `<strong>Slot Status:</strong><br>`;
            for (const [slot, booked] of Object.entries(slots)) {
              info += `${slot}: ${booked ? "❌ Booked" : "✅ Available"}<br>`;
            }
            if (slotInfo) slotInfo.innerHTML = info;
          } else {
            if (slotInfo) slotInfo.textContent = `📅 Selected Date: ${dayEl.dataset.date}`;
          }
        });
      });
    }
  }

  // 🔹 Month navigation
  if (prevMonthBtn) prevMonthBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
  });
  if (nextMonthBtn) nextMonthBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
  });

  renderCalendar();
})();
