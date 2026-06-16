/* ------------------------------
   booking.js (Clean + Synced with DB + Map Fix)
------------------------------ */

const steps = document.querySelectorAll('.form-step');
const nextBtns = document.querySelectorAll('.next-btn');
const prevBtns = document.querySelectorAll('.prev-btn');
const progress = document.getElementById('progress');
const progressSteps = document.querySelectorAll('.progress-step');
const summaryBox = document.getElementById('summaryBox');
const venueSelect = document.getElementById('venueSelect');
const venueMap = document.getElementById('venueMap');
const timeSlot = document.getElementById('timeSlot');
const eventDateInput = document.getElementById("event_date");
const priceField = document.getElementById("price");
const priceDisplay = document.getElementById("priceDisplay");

let currentStep = 0;

/* ---------- VENUES with Labels (Display vs Clean Value) ---------- */
const venuesByEvent = {
  wedding: [
    { name: "Bolgatty Palace (Premium)", value: "Bolgatty Palace" },
    { name: "Grand Hyatt Kochi (Premium)", value: "Grand Hyatt Kochi" },
    { name: "Taj Malabar Resort (Premium)", value: "Taj Malabar Resort" },
    { name: "Le Meridien Kochi (Premium)", value: "Le Meridien Kochi" },
    { name: "Raviz Convention Center (Premium)", value: "Raviz Convention Center" },
    { name: "Holiday Inn Kochi (Standard)", value: "Holiday Inn Kochi" },
    { name: "Lulu Bolgatty Convention Center (Premium)", value: "Lulu Bolgatty Convention Center" },
    { name: "Gokulam Park Hotel (Budget)", value: "Gokulam Park Hotel" },
    { name: "Cherai Beach Resorts (Standard)", value: "Cherai Beach Resorts" },
    { name: "Dream Hotel Kochi (Standard)", value: "Dream Hotel Kochi" }
  ],
  birthday: [
    { name: "Flora Airport Hotel (Budget)", value: "Flora Airport Hotel" },
    { name: "Kochi Marriott Hotel (Premium)", value: "Kochi Marriott Hotel" },
    { name: "Olive Downtown Kochi (Standard)", value: "Olive Downtown Kochi" },
    { name: "Hotel Abad Plaza (Budget)", value: "Hotel Abad Plaza" },
    { name: "Casino Hotel (Standard)", value: "Casino Hotel" },
    { name: "Dream Hotel Kochi (Standard)", value: "Dream Hotel Kochi" },
    { name: "Bolgatty Lawn (Budget)", value: "Bolgatty Lawn" },
    { name: "Abad Atrium (Budget)", value: "Abad Atrium" },
    { name: "YMCA Banquet Hall (Budget)", value: "YMCA Banquet Hall" },
    { name: "BTH Sarovaram (Budget)", value: "BTH Sarovaram" }
  ],
  funeral: [
    { name: "Town Hall Kochi (Budget)", value: "Town Hall Kochi" },
    { name: "Community Hall (Budget)", value: "Community Hall" },
    { name: "Church / Religious Hall (Free)", value: "Church / Religious Hall" },
    { name: "Crematorium Hall (Budget)", value: "Crematorium Hall" },
    { name: "VJT Hall (Standard)", value: "VJT Hall" },
    { name: "Municipal Community Center (Budget)", value: "Municipal Community Center" },
    { name: "Panchayat Hall (Budget)", value: "Panchayat Hall" },
    { name: "Public Auditorium (Standard)", value: "Public Auditorium" },
    { name: "NGO/Trust Hall (Budget)", value: "NGO/Trust Hall" },
    { name: "Open Ground Shelter (Low-Cost)", value: "Open Ground Shelter" }
  ],
  conference: [
    { name: "Kochi Marriott Hotel (Premium)", value: "Kochi Marriott Hotel" },
    { name: "Crystal Convention Center (Premium)", value: "Crystal Convention Center" },
    { name: "Le Meridien Conference Hall (Premium)", value: "Le Meridien Conference Hall" },
    { name: "Crowne Plaza Conference Center (Premium)", value: "Crowne Plaza Conference Center" },
    { name: "Lulu International Convention Center (Premium)", value: "Lulu International Convention Center" },
    { name: "Holiday Inn Conference Room (Standard)", value: "Holiday Inn Conference Room" },
    { name: "IMA House (Standard)", value: "IMA House" },
    { name: "CIAL Convention Center (Premium)", value: "CIAL Convention Center" },
    { name: "Xavier Institute Auditorium (Budget)", value: "Xavier Institute Auditorium" },
    { name: "Startup Village Hall (Budget)", value: "Startup Village Hall" }
  ],
  concert: [
    { name: "Grand Hyatt Kochi (Premium)", value: "Grand Hyatt Kochi" },
    { name: "Bolgatty Palace Grounds (Premium)", value: "Bolgatty Palace Grounds" },
    { name: "Jawaharlal Nehru Stadium (Premium)", value: "Jawaharlal Nehru Stadium" },
    { name: "Marine Drive Grounds (Standard)", value: "Marine Drive Grounds" },
    { name: "Rajiv Gandhi Indoor Stadium (Premium)", value: "Rajiv Gandhi Indoor Stadium" },
    { name: "Kumarakom Lake Resort Stage (Premium)", value: "Kumarakom Lake Resort Stage" },
    { name: "Durbar Hall Ground (Standard)", value: "Durbar Hall Ground" },
    { name: "Regional Sports Centre (Premium)", value: "Regional Sports Centre" },
    { name: "Open Air Fort Ground (Budget)", value: "Open Air Fort Ground" },
    { name: "Kaloor Indoor Stadium (Premium)", value: "Kaloor Indoor Stadium" }
  ],
  gathering: [
    { name: "Isola Di Cocco (Standard)", value: "Isola Di Cocco" },
    { name: "Crowne Plaza Kochi (Premium)", value: "Crowne Plaza Kochi" },
    { name: "Bolgatty Event Lawn (Standard)", value: "Bolgatty Event Lawn" },
    { name: "Casino Hotel (Standard)", value: "Casino Hotel" },
    { name: "Greenfield Club Hall (Budget)", value: "Greenfield Club Hall" },
    { name: "Heritage Hall (Budget)", value: "Heritage Hall" },
    { name: "Abad Nucleus Mall Banquet (Budget)", value: "Abad Nucleus Mall Banquet" },
    { name: "BTH Sarovaram Garden (Budget)", value: "BTH Sarovaram Garden" },
    { name: "Yacht Club Kochi (Premium)", value: "Yacht Club Kochi" },
    { name: "Open Park Pavilion (Low-Cost)", value: "Open Park Pavilion" }
  ]
};


const mapURLs = {
  // 🏨 Premium Hotels & Resorts
  "taj malabar resort": "https://www.google.com/maps?q=Taj+Malabar+Resort+Kochi&output=embed",
  "grand hyatt kochi": "https://www.google.com/maps?q=Grand+Hyatt+Kochi&output=embed",
  "le meridien kochi": "https://www.google.com/maps?q=Le+Meridien+Kochi&output=embed",
  "raviz convention center": "https://www.google.com/maps?q=Raviz+Convention+Center+Kochi&output=embed",
  "holiday inn kochi": "https://www.google.com/maps?q=Holiday+Inn+Kochi&output=embed",
  "lulu bolgatty convention center": "https://www.google.com/maps?q=Lulu+Bolgatty+Convention+Center+Kochi&output=embed",
  "kochi marriott hotel": "https://www.google.com/maps?q=Kochi+Marriott+Hotel&output=embed",
  "crowne plaza kochi": "https://www.google.com/maps?q=Crowne+Plaza+Kochi&output=embed",
  "dream hotel kochi": "https://www.google.com/maps?q=Dream+Hotel+Kochi&output=embed",
  "casino hotel": "https://www.google.com/maps?q=Casino+Hotel+Kochi&output=embed",
  "flora airport hotel": "https://www.google.com/maps?q=Flora+Airport+Hotel+Kochi&output=embed",
  "olive downtown kochi": "https://www.google.com/maps?q=Olive+Downtown+Kochi&output=embed",
  "gokulam park hotel": "https://www.google.com/maps?q=Gokulam+Park+Hotel+Kochi&output=embed",
  "cherai beach resorts": "https://www.google.com/maps?q=Cherai+Beach+Resorts&output=embed",
  "isola di cocco": "https://www.google.com/maps?q=Isola+Di+Cocco+Kochi&output=embed",
  "yacht club kochi": "https://www.google.com/maps?q=Yacht+Club+Kochi&output=embed",

  // 🎉 Banquet Halls & Convention Centers
  "crystal convention center": "https://www.google.com/maps?q=Crystal+Convention+Center+Kochi&output=embed",
  "ima house": "https://www.google.com/maps?q=IMA+House+Kochi&output=embed",
  "cial convention center": "https://www.google.com/maps?q=CIAL+Convention+Center+Kochi&output=embed",
  "xavier institute auditorium": "https://www.google.com/maps?q=Xavier+Institute+Kochi&output=embed",
  "startup village hall": "https://www.google.com/maps?q=Startup+Village+Kochi&output=embed",
  "lulu international convention center": "https://www.google.com/maps?q=Lulu+International+Convention+Center+Kochi&output=embed",
  "holiday inn conference room": "https://www.google.com/maps?q=Holiday+Inn+Conference+Room+Kochi&output=embed",

  // 🎂 Birthday / Budget Halls
  "hotel abad plaza": "https://www.google.com/maps?q=Hotel+Abad+Plaza+Kochi&output=embed",
  "abad atrium": "https://www.google.com/maps?q=Abad+Atrium+Kochi&output=embed",
  "ymca banquet hall": "https://www.google.com/maps?q=YMCA+Banquet+Hall+Kochi&output=embed",
  "bth sarovaram": "https://www.google.com/maps?q=BTH+Sarovaram+Kochi&output=embed",
  "abad nucleus mall banquet": "https://www.google.com/maps?q=Abad+Nucleus+Mall+Banquet+Kochi&output=embed",
  "bth sarovaram garden": "https://www.google.com/maps?q=BTH+Sarovaram+Garden+Kochi&output=embed",

  // ⚰ Funeral Venues
  "town hall kochi": "https://www.google.com/maps?q=Town+Hall+Kochi&output=embed",
  "community hall": "https://www.google.com/maps?q=Community+Hall+Kochi&output=embed",
  "church / religious hall": "https://www.google.com/maps?q=Church+Hall+Kochi&output=embed",
  "crematorium hall": "https://www.google.com/maps?q=Crematorium+Hall+Kochi&output=embed",
  "vjt hall": "https://www.google.com/maps?q=VJT+Hall+Kochi&output=embed",
  "municipal community center": "https://www.google.com/maps?q=Municipal+Community+Center+Kochi&output=embed",
  "panchayat hall": "https://www.google.com/maps?q=Panchayat+Hall+Kochi&output=embed",
  "public auditorium": "https://www.google.com/maps?q=Public+Auditorium+Kochi&output=embed",
  "ngo/trust hall": "https://www.google.com/maps?q=Trust+Hall+Kochi&output=embed",
  "open ground shelter": "https://www.google.com/maps?q=Open+Ground+Kochi&output=embed",

  // 🎤 Concert Venues
  "bolgatty palace grounds": "https://www.google.com/maps?q=Bolgatty+Palace+Grounds+Kochi&output=embed",
  "jawaharlal nehru stadium": "https://www.google.com/maps?q=Jawaharlal+Nehru+Stadium+Kochi&output=embed",
  "marine drive grounds": "https://www.google.com/maps?q=Marine+Drive+Grounds+Kochi&output=embed",
  "rajiv gandhi indoor stadium": "https://www.google.com/maps?q=Rajiv+Gandhi+Indoor+Stadium+Kochi&output=embed",
  "kumarakom lake resort stage": "https://www.google.com/maps?q=Kumarakom+Lake+Resort&output=embed",
  "durbar hall ground": "https://www.google.com/maps?q=Durbar+Hall+Ground+Kochi&output=embed",
  "regional sports centre": "https://www.google.com/maps?q=Regional+Sports+Centre+Kochi&output=embed",
  "open air fort ground": "https://www.google.com/maps?q=Fort+Ground+Kochi&output=embed",
  "kaloor indoor stadium": "https://www.google.com/maps?q=Kaloor+Indoor+Stadium+Kochi&output=embed",

  // 👥 Gathering Venues
  "bolgatty event lawn": "https://www.google.com/maps?q=Bolgatty+Event+Lawn+Kochi&output=embed",
  "greenfield club hall": "https://www.google.com/maps?q=Greenfield+Club+Hall+Kochi&output=embed",
  "heritage hall": "https://www.google.com/maps?q=Heritage+Hall+Kochi&output=embed",
  "open park pavilion": "https://www.google.com/maps?q=Open+Park+Pavilion+Kochi&output=embed"
};

/* ---------- PROGRESS + STEP ---------- */
function updateStep() {
  steps.forEach((step, index) => {
    step.classList.remove('active');
    if (index === currentStep) step.classList.add('active');
  });

  progress.style.width = (currentStep / (steps.length - 1)) * 100 + "%";
  progressSteps.forEach((step, index) => step.classList.toggle('active', index <= currentStep));

  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* Validation check before moving next */
function validateStep() {
  const inputs = steps[currentStep].querySelectorAll("input[required], select[required], textarea[required]");
  let valid = true;

  inputs.forEach(input => {
    if (!input.value.trim()) {
      input.classList.add("error");
      valid = false;
      setTimeout(() => input.classList.remove("error"), 1500);
    }
  });
  return valid;
}

/* Next Button */
nextBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    if (!validateStep()) return;

    if (currentStep === 2) generateSummary();
    if (currentStep < steps.length - 1) {
      currentStep++;
      updateStep();
    }
  });
});

/* Prev Button */
prevBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    if (currentStep > 0) {
      currentStep--;
      updateStep();
    }
  });
});

/* ---------- VENUE FILTER ---------- */
window.filterVenues = function () {
  const event = document.getElementById("event").value;
  venueSelect.innerHTML = '<option value="">-- Select Venue --</option>';

  if (venuesByEvent[event]) {
    venuesByEvent[event].forEach(venue => {
      const option = document.createElement("option");
      option.value = venue.value;
      option.textContent = venue.name;
      venueSelect.appendChild(option);
    });
  }
};

/* ---------- HANDLE VENUE CHANGE ---------- */
window.handleVenueChange = function () {
  const venue = venueSelect.value;  // selected venue
  const priceField = document.getElementById("price");
  const priceDisplay = document.getElementById("priceDisplay");

  if (!venue) {
    priceField.value = 0;
    priceDisplay.textContent = "";
    venueMap.src = "https://www.google.com/maps?q=kochi&output=embed";
    return;
  }

  // Normalize venue for map lookup
  const venueKey = venue.toLowerCase().trim();
  venueMap.src = mapURLs[venueKey] || "https://www.google.com/maps?q=kochi&output=embed";

  // Fetch price from DB
  fetch(`get_price.php?venue=${encodeURIComponent(venue)}`)
    .then(res => res.json())
    .then(data => {
      priceField.value = data.price || 0;
      priceDisplay.textContent = "💰 Price: ₹" + (data.price || 0);
    })
    .catch(err => {
      console.error("Price fetch error:", err);
      priceField.value = 0;
      priceDisplay.textContent = "⚠ Price unavailable";
    });
};


/* ---------- SUMMARY ---------- */
function generateSummary() {
  const name = document.getElementById('attendeeName').value;
  const email = document.getElementById('attendeeEmail').value;
  const phone = document.getElementById('attendeePhone').value;
  const event = document.getElementById('event').value;
  const venue = venueSelect.options[venueSelect.selectedIndex]?.text || "";
  const date = document.getElementById('event_date').value;
  const slot = timeSlot.value;
  const message = document.getElementById('message').value;
  const price = document.getElementById('price').value;

  summaryBox.innerHTML = `
    <div class="summary-wrapper">
      <!-- Left box -->
      <div class="summary-details">
        <h3>📋 Booking Details</h3>
        <p><strong>Name:</strong> ${name}</p>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Phone:</strong> ${phone}</p>
        <p><strong>Event:</strong> ${event}</p>
        <p><strong>Venue:</strong> ${venue}</p>
        <p><strong>Date:</strong> ${date}</p>
        <p><strong>Time Slot:</strong> ${slot}</p>
        <p><strong>Message:</strong> ${message || "N/A"}</p>
      </div>

      <!-- Right box -->
      <div class="summary-price">
        <h3>💰 Price</h3>
        <p><span>Amount:</span> ₹${price}</p>
      </div>
    </div>
  `;
}


/* ---------- SLOT CHECK ---------- */
eventDateInput.addEventListener("change", checkSlots);
venueSelect.addEventListener("change", checkSlots);

function checkSlots() {
  const venue = venueSelect.value;
  const eventDate = eventDateInput.value;
  if (!venue || !eventDate) return;

  fetch(`get_booked_slots.php?venue=${venue}&event_date=${eventDate}`)
    .then(res => res.json())
    .then(booked => {
      Array.from(timeSlot.options).forEach(opt => {
        if (!opt.value) return;
        if (booked.includes(opt.value)) {
          opt.disabled = true;
          opt.classList.add("disabled");
        } else {
          opt.disabled = false;
          opt.classList.remove("disabled");
        }
      });
    })
    .catch(err => console.error("Slot check error:", err));
}

/* ---------- INIT ---------- */
updateStep();

/* ---------- Extra Animations ---------- */
document.head.insertAdjacentHTML("beforeend", `
  <style>
    .form-step { opacity: 0; transform: translateY(15px); transition: all 0.4s ease; }
    .form-step.active { opacity: 1; transform: translateY(0); }
    input.error, select.error, textarea.error {
      border: 1px solid #ff6b6b !important;
      box-shadow: 0 0 6px rgba(255,0,0,0.6);
    }
    select option.disabled {
      color: #aaa !important;
      background: #333 !important;
    }
  </style>
`);
