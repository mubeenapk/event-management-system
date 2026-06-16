<?php
include 'config.php';
session_start();

$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id) {
    die("<div class='error-box'>⚠ Invalid request. No booking selected.</div>");
}

// Get booking info first
$stmt = $conn->prepare("SELECT name, event_type, venue, event_date, time_slot, price, status 
                        FROM bookings WHERE id=?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("<div class='error-box'>❌ Booking not found.</div>");
}

// Save original status before updating
$original_status = $booking['status'];

// Update status to Cancelled only if not already cancelled
if ($original_status !== 'Cancelled') {
    $q = $conn->prepare("UPDATE bookings SET status='Cancelled' WHERE id=?");
    $q->bind_param("i", $booking_id);
    $q->execute();
    $q->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Cancelled</title>
  <style>
    body { font-family: Arial, sans-serif; background:#111; color:#fff; text-align:center; padding:50px; }
    .cancel-box { max-width:600px; margin:auto; padding:30px; background:#1e1e1e; border-radius:12px; }
    .cancel-box h2 { color:#f44336; }
    .cancel-box strong { color:#ffcc00; }
    .btn { display:inline-block; margin-top:15px; padding:10px 18px; background:#ff4081; color:#fff; border-radius:6px; text-decoration:none; }
    .btn:hover { background:#e73370; }
  </style>
</head>
<body>
  <div class="cancel-box">
    <h2>❌ Booking Cancelled</h2>
    
    <?php if (!empty($booking)): ?>
      <p><strong>Name:</strong> <?= htmlspecialchars($booking['name']) ?></p>
      <p><strong>Event:</strong> <?= htmlspecialchars($booking['event_type']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($booking['event_date']) ?> (<?= htmlspecialchars($booking['time_slot']) ?>)</p>
      <p><strong>Venue:</strong> <?= htmlspecialchars($booking['venue']) ?></p>
      <p><strong>Amount:</strong> ₹<?= number_format($booking['price'], 2) ?></p>
      
      <?php if ($original_status === 'Paid' || $original_status === 'Confirmed'): ?>
        <p style="color:orange;">⚠ This booking was already paid. Please contact admin for refund.</p>
      <?php elseif ($original_status === 'Pending'): ?>
        <p>✅ Booking was cancelled before payment.</p>
      <?php elseif ($original_status === 'Cancelled'): ?>
        <p>❌ This booking was already cancelled earlier.</p>
      <?php endif; ?>
    <?php endif; ?>

    <a href="home.php" class="btn">🏠 Back to Home</a>
    <a href="my_bookings.php" class="btn">📖 My Bookings</a>
  </div>
</body>
</html>
