<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ivento";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$booking_id = $_GET['booking_id'] ?? 0;

// Fetch payment details
$stmt = $conn->prepare("SELECT method, upi_app, upi_id, amount, payment_date, status FROM payments WHERE booking_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank You</title>
  <style>
    body {
      background-color: #121212;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .thank-box {
      background: #1e1e1e;
      padding: 30px;
      border-radius: 12px;
      width: 450px;
      box-shadow: 0 0 15px rgba(0,0,0,0.5);
      text-align: center;
    }
    h2 {
      color: #4CAF50;
      margin-bottom: 10px;
    }
    p {
      margin: 8px 0;
    }
    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 20px;
      border-radius: 8px;
      background: #4CAF50;
      color: white;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #45a049;
    }
  </style>
</head>
<body>
  <div class="thank-box">
    <h2>✅ Thank you for your booking!</h2>
    <?php if ($payment): ?>
      <p>Your payment was <b><?php echo htmlspecialchars($payment['status']); ?></b>.</p>
      <p>Amount Paid: ₹<?php echo htmlspecialchars($payment['amount']); ?></p>
      <p>Method: <?php echo htmlspecialchars($payment['method']); ?></p>
      <?php if ($payment['method'] === "UPI"): ?>
        <p>UPI App: <?php echo htmlspecialchars($payment['upi_app']); ?></p>
        <p>UPI ID: <?php echo htmlspecialchars($payment['upi_id']); ?></p>
      <?php endif; ?>
      <p>Date: <?php echo htmlspecialchars($payment['payment_date']); ?></p>
    <?php else: ?>
      <p>⚠ No payment record found for this booking.</p>
    <?php endif; ?>
    <a href="booking.php" class="btn">Go Back to Booking Page</a>
  </div>
</body>
</html>
