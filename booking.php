<?php // booking.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Booking</title>
  <link rel="stylesheet" href="booking.css">
</head>
<body>
  <!-- Floating Accent Glow -->
  <div class="floating-accent"></div>

  <div class="booking-container">
    <h1>Book Your Event</h1>

    <!-- Progress Bar -->
    <div class="progressbar">
      <div id="progress"></div>
      <div class="progress-step active">1</div>
      <div class="progress-step">2</div>
      <div class="progress-step">3</div>
      <div class="progress-step">4</div>
    </div>

    <!-- ✅ Single Form -->
    <form action="submit-booking.php" method="POST" id="bookingForm">

      <!-- Step 1 -->
      <div class="form-step active">
        <h2>Attendee Details</h2>
        <input type="text" id="attendeeName" name="name" placeholder="Full Name" required>
        <input type="email" id="attendeeEmail" name="email" placeholder="Email" required>
        <input type="tel" id="attendeePhone" name="phone" placeholder="Phone Number" required>
        <button type="button" class="next-btn">Next</button>
      </div>

      <!-- Step 2 -->
      <div class="form-step">
        <h2>Event & Venue</h2>
        
        <!-- Event Selection -->
        <select name="event" id="event" required onchange="filterVenues()">
          <option value="">-- Select Event --</option>
          <option value="wedding">Wedding</option>
          <option value="conference">Conference</option>
          <option value="birthday">Birthday</option>
          <option value="concert">Concert</option>
          <option value="funeral">Funeral</option>
          <option value="gathering">Gathering</option>
        </select>

        <!-- Venue Selection -->
        <select id="venueSelect" name="venue" required onchange="handleVenueChange()">
          <option value="">-- Select Venue --</option>
        </select>

        <!-- Map -->
        <div class="map-container">
          <iframe id="venueMap" src="https://www.google.com/maps?q=kochi&output=embed" allowfullscreen loading="lazy"></iframe>
        </div>

        <!-- Price Display -->
        <p id="priceDisplay" class="price"></p>
        <input type="hidden" name="price" id="price">

        <button type="button" class="prev-btn">Back</button>
        <button type="button" class="next-btn">Next</button>
      </div>

      <!-- Step 3 -->
      <div class="form-step">
        <h2>Schedule</h2>
        <h3>Select Event Date</h3>

        <!-- 📅 Calendar Header -->
        <div class="calendar-header">
          <button type="button" id="prevMonth" class="month-btn">◀</button>
          <h3 id="calendarTitle"></h3>
          <button type="button" id="nextMonth" class="month-btn">▶</button>
        </div>

        <!-- Weekdays row -->
        <div class="calendar-weekdays">
          <div>Sun</div>
          <div>Mon</div>
          <div>Tue</div>
          <div>Wed</div>
          <div>Thu</div>
          <div>Fri</div>
          <div>Sat</div>
        </div>

        <!-- Calendar days -->
        <div id="calendar" class="calendar-grid"></div>

        <!-- Legend -->
        <div class="calendar-legend">
          <div class="legend-item"><span class="legend-dot available"></span> Available</div>
          <div class="legend-item"><span class="legend-dot partial"></span> Partial</div>
          <div class="legend-item"><span class="legend-dot full"></span> Full</div>
          <div class="legend-item"><span class="legend-dot past"></span> Past</div>
        </div>

        <!-- Selected date -->
        <p id="slotInfo"></p>
        <input type="hidden" id="event_date" name="event_date" required>

        <!-- Slots -->
        <label for="timeSlot">Time Slot</label>
        <select id="timeSlot" name="time_slot" required>
          <option value="">-- Select Time Slot --</option>
          <option value="Morning">Morning (9AM - 12PM)</option>
          <option value="Afternoon">Afternoon (1PM - 5PM)</option>
          <option value="Evening">Evening (6PM - 10PM)</option>
        </select>

        <!-- Message -->
        <label for="message">Additional Details</label>
        <textarea id="message" name="message" placeholder="Additional Details"></textarea>

        <!-- Price -->
        <p id="priceDisplay" class="price"></p>
        <input type="hidden" name="price" id="price">

        <button type="button" class="prev-btn">Back</button>
        <button type="button" class="next-btn">Next</button>
      </div>

      <!-- Step 4 -->
      <div class="form-step">
        <h2>Summary</h2>
        <div id="summaryBox" class="summary-box"></div>
        <button type="button" class="prev-btn">Back</button>
        <button type="submit">Proceed to Payment</button>
      </div>
    </form>
  </div>

  <!-- Scripts -->
  <script src="calendar.js"></script>
  <script src="booking.js"></script>
</body>
</html>
