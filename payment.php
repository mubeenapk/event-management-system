<?php
include 'config.php';
session_start();

$booking_id = $_GET['booking_id'] ?? null;

if (!$booking_id) {
    die("⚠ Invalid request. No booking selected.");
}

// Fetch booking details
$stmt = $conn->prepare("SELECT venue, price FROM bookings WHERE id=?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("⚠ Booking not found.");
}

$venue = $booking['venue'];

// ✅ Always recheck from hotels table
$price = 0;
$check = $conn->prepare("SELECT price FROM hotels WHERE hotel_name=? LIMIT 1");
$check->bind_param("s", $venue);
$check->execute();
$check->bind_result($venuePrice);
if ($check->fetch()) {
    $price = $venuePrice;
} else {
    $price = $booking['price']; // fallback if custom or missing
}
$check->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - Ivento</title>
  <link rel="stylesheet" href="payment.css">
</head>
<body>

<div class="payment-container">
  <h2>💳 Complete Your Payment</h2>

  <!-- ✅ Only showing price -->
  <div class="booking-summary">
    <p><strong>💰 Amount to Pay:</strong> ₹<?= number_format($price, 2) ?></p>
  </div>

  <form action="process_payment.php" method="POST" class="payment-form">
    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking_id) ?>">

    <label for="method">Select Payment Method</label>
    <select id="method" name="payment_method" required>
      <option value="">-- Choose Method --</option>
      <option value="UPI">UPI</option>
      <option value="Card">Card</option>
      <option value="NetBanking">Net Banking</option>
      <option value="Cash">Cash</option>
    </select>

    <!-- UPI Section -->
    <div id="upiFields" class="extra-fields">
      <label for="upi_app">UPI App</label>
      <select id="upi_app" name="upi_app">
        <option value="">-- Select UPI App --</option>
        <option value="GooglePay">Google Pay</option>
        <option value="PhonePe">PhonePe</option>
        <option value="Paytm">Paytm</option>
      </select>

      <label for="upi_id">UPI ID</label>
      <input type="text" id="upi_id" name="upi_id" placeholder="example@upi">
    </div>

    <!-- Card Section -->
    <div id="cardFields" class="extra-fields">
      <label for="card_number">Card Number</label>
      <input type="text" id="card_number" name="card_number" placeholder="xxxx-xxxx-xxxx-xxxx">
    </div>

    <!-- NetBanking Section -->
    <div id="netbankingFields" class="extra-fields">
      <label for="bank">Choose Bank</label>
      <select id="bank" name="bank">
        <option value="">-- Select Bank --</option>
        <option value="SBI">State Bank of India</option>
        <option value="HDFC">HDFC Bank</option>
        <option value="ICICI">ICICI Bank</option>
      </select>
    </div>

    <div class="buttons">
      <button type="submit" class="confirm-btn">✅ Confirm Payment</button>
      <a href="cancel.php?booking_id=<?= htmlspecialchars($booking_id) ?>" class="cancel-btn">❌ Cancel Booking</a>
    </div>
  </form>
</div>

<script>
document.querySelectorAll('.extra-fields').forEach(div => div.style.display = 'none');

document.getElementById('method').addEventListener('change', function() {
  document.querySelectorAll('.extra-fields').forEach(div => div.style.display = 'none');

  if (this.value === 'UPI') document.getElementById('upiFields').style.display = 'block';
  if (this.value === 'Card') document.getElementById('cardFields').style.display = 'block';
  if (this.value === 'NetBanking') document.getElementById('netbankingFields').style.display = 'block';
});
</script>

</body>
</html>
