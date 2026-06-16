<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ivento | Professional Event Solutions</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="home.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

<header class="navbar">
  <div class="logo">ivento</div>
  <nav>
    <ul>
      <li><a href="home.php">Home</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#events">Events</a></li>
      <li><a href="#gallery">Gallery</a></li>
      <li><a href="#calendarSection">Calendar</a></li>
      <li><a href="booking.php">Book</a></li>
      <li><a href="#feedback">Feedback</a></li>
      <li><a href="#contact">Contact</a></li>

      <?php if (isset($_SESSION['user_id'])): ?>
        <li class="dropdown">
          <a href="#" class="dropbtn">Account <span class="arrow">▼</span></a>
          <div class="dropdown-content">
            <a href="my_bookings.php">My Bookings</a>
            <a href="my_feedback.php">My Feedback</a>
            <a href="logout.php">Logout</a>
          </div>
        </li>
      <?php else: ?>
        <li><a href="auth.php">Login / Register</a></li>
      <?php endif; ?>

      <li><a href="admin_login.php">Admin Login</a></li>
    </ul>
  </nav>
</header>

<!-- Hero Section -->
<section class="hero">
  <video autoplay muted loop playsinline class="hero-video">
    <source src="media/hero-video.mp4" type="video/mp4" />
  </video>
  <div class="overlay">
    <h1 class="hero-title">Professional Event Management</h1>
    <p class="hero-subtitle">Turning your special moments into unforgettable experiences</p>
    <?php if (isset($_SESSION['user_id'])): ?>
  <a href="booking.php" class="btn">Book Now</a>
<?php else: ?>
  <a href="auth.php" class="btn">Book Now</a>
<?php endif; ?>
</section>

<!-- Services Section -->
<section id="services">
  <h2>Our Services</h2>
  <div class="service-grid">
    <div class="card"><h3>Event Planning</h3><p>Personalized event strategies crafted for your needs.</p></div>
    <div class="card"><h3>Venue Booking</h3><p>Book the perfect venue with complete convenience.</p></div>
    <div class="card"><h3>Technical Support</h3><p>From sound to stage, we cover all technical setups.</p></div>
  </div>
</section>

<!-- Events -->
<section id="events">
  <h2>Events We Organize</h2>
  <div class="event-grid">
    <div class="event-card">Weddings</div>
    <div class="event-card">Conference</div>
    <div class="event-card">Birthdays</div>
    <div class="event-card">Concert</div>
    <div class="event-card">Funerals</div>
    <div class="event-card">Gathering</div>
  </div>
</section>

<!-- Gallery -->
<section id="gallery">
  <h2>Our Events</h2>
  <div class="gallery-grid">
    <img src="images/event1.jpg" alt="Event 1">
    <img src="images/event2.jpg" alt="Event 2">
    <img src="images/event3.jpg" alt="Event 3">
    <img src="images/event4.jpg" alt="Event 4">
  </div>
</section>

<!-- 📅 Calendar Section -->
<section id="calendarSection">
  <h2>Check Availability</h2>
  <div class="calendar-header">
    <button id="prevMonth">◀</button>
    <h3 id="calendarTitle"></h3>
    <button id="nextMonth">▶</button>
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

</section>



<!-- Feedback -->
<section id="feedback">
  <h2>What Our Clients Say</h2>
  <div class="testimonial-grid">
    <div class="testimonial-card">
      <p>"Ivento made our wedding unforgettable! Highly professional team."</p>
      <h4>- Anjali & Rohit</h4>
    </div>
    <div class="testimonial-card">
      <p>"Amazing support for our conference. Everything was smooth."</p>
      <h4>- TechWorld Ltd.</h4>
    </div>
    <?php
    include 'config.php';
    $result = $conn->query("SELECT name, message FROM feedback ORDER BY created_at DESC LIMIT 3");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="testimonial-card">';
            echo '<p>"' . htmlspecialchars($row['message']) . '"</p>';
            echo '<h4>- ' . htmlspecialchars($row['name']) . '</h4>';
            echo '</div>';
        }
    }
    $conn->close();
    ?>
  </div>

  <div class="feedback-form-container">
    <h3>Leave Your Feedback</h3>
    <form action="submit_feedback.php" method="POST">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" placeholder="Your Feedback" required></textarea>
      <button type="submit">Submit Feedback</button>
    </form>
  </div>
</section>

<!-- Contact -->
<section id="contact">
  <h2>Contact Us</h2>
  <form class="contact-form" onsubmit="sendWhatsApp(event)">
    <input type="text" id="name" placeholder="Your Name" required>
    <input type="email" id="email" placeholder="Your Email" required>
    <textarea id="message" placeholder="Your Message" required></textarea>
    <button type="submit">Send Message</button>
  </form>
</section>

<!-- Footer -->
<footer>
  <div class="social-icons">
    <a href="#"><i class="fab fa-facebook-f"></i></a>
    <a href="#"><i class="fab fa-instagram"></i></a>
    <a href="#"><i class="fab fa-twitter"></i></a>
  </div>
  <p>&copy; 2025 ivento. All rights reserved.</p>
</footer>

<!-- ✅ Custom scripts -->
<script>
// Dropdown toggle
document.querySelectorAll(".dropbtn").forEach(btn => {
  btn.addEventListener("click", function(e) {
    e.preventDefault();
    this.parentElement.classList.toggle("show");
  });
});

window.addEventListener("click", e => {
  if (!e.target.matches('.dropbtn')) {
    document.querySelectorAll(".dropdown").forEach(dd => dd.classList.remove("show"));
  }
});

// WhatsApp
function sendWhatsApp(e) {
  e.preventDefault();
  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const message = document.getElementById("message").value;
  const phone = "917907124896";
  const text = `Hello, my name is ${name}. My email is ${email}. Message: ${message}`;
  window.open(`https://wa.me/${phone}?text=${encodeURIComponent(text)}`, "_blank");
}
</script>

<script src="calendar.js"></script>
</body>
</html>
