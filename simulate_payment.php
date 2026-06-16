<?php
$booking_id = $_POST['booking_id'] ?? 0;
$amount = $_POST['amount'] ?? 0;
$method = $_POST['method'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Simulate Payment</title>
  <style>
    body {
      background-color: #0d0d0d;
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .box {
      background: #1a1a1a;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.6);
    }
    button {
      margin: 10px;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
    }
    .success { background: #4CAF50; color: #fff; }
    .fail { background: #f44336; color: #fff; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Simulate Payment</h2>
    <p>Booking ID: <?php echo htmlspecialchars($booking_id); ?></p>
    <p>Amount: ₹<?php echo htmlspecialchars($amount); ?></p>
    <p>Method: <?php echo htmlspecialchars($method); ?></p>

    <form method="POST" action="payment-success.php" style="display:inline;">
      <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
      <button type="submit" class="success">Simulate Success</button>
    </form>

    <form method="POST" action="payment-failed.php" style="display:inline;">
      <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
      <button type="submit" class="fail">Simulate Failure</button>
    </form>
  </div>
</body>
</html>
