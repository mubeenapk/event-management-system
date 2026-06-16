<?php
session_start();
include 'config.php';

$booking_id = $_GET['booking_id'] ?? 0;

// Default values
$booking = null;

if ($booking_id) {
    $stmt = $conn->prepare("SELECT id, name, email, event_type, venue, event_date, time_slot, price, status 
                            FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Success - Ivento</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #0d0d0d;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }
    .success-box {
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
      padding: 30px;
      border-radius: 14px;
      text-align: center;
      max-width: 600px;
      width: 95%;
      box-shadow: 0 6px 20px rgba(0,0,0,0.6);
    }
    .success-box h2 {
      color: #4CAF50;
      font-size: 2rem;
      margin-bottom: 10px;
    }
    .success-box p {
      font-size: 1rem;
      margin: 8px 0;
    }
    .details {
      margin: 20px 0;
      text-align: left;
      background: rgba(255,255,255,0.05);
      padding: 15px;
      border-radius: 10px;
    }
    .details strong {
      color: #ffcc00;
    }
    .btn {
      display: inline-block;
      margin: 10px;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
      transition: background 0.3s;
    }
    .btn.home {
      background: #ff4081;
      color: #fff;
    }
    .btn.home:hover {
      background: #e73370;
    }
    .btn.bookings {
      background: #333;
      color: #fff;
    }
    .btn.bookings:hover {
      background: #555;
    }
  </style>
</head>
<body>
  <div class="success-box">
    <h2>✅ Payment Successful!</h2>
    <p>Your booking has been confirmed.</p>

    <?php if ($booking): ?>
      <div class="details">
        <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking['id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($booking['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
        <p><strong>Event:</strong> <?= htmlspecialchars($booking['event_type']) ?></p>
        <p><strong>Venue:</strong> <?= htmlspecialchars($booking['venue']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($booking['event_date']) ?></p>
        <p><strong>Time Slot:</strong> <?= htmlspecialchars($booking['time_slot']) ?></p>
        <p><strong>Amount Paid:</strong> ₹<?= number_format($booking['price'], 2) ?></p>
      </div>
    <?php else: ?>
      <p>⚠ Booking details not found.</p>
    <?php endif; ?>

    <a href="home.php" class="btn home">🏠 Go Home</a>
    <a href="my_bookings.php" class="btn bookings">📖 View My Bookings</a>
  </div>
</body>
</html>
