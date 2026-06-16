<?php
include 'config.php';
session_start();

// If user submitted email
$searchEmail = $_POST['search_email'] ?? null;
$bookings = [];

if ($searchEmail) {
    $stmt = $conn->prepare("SELECT id, event_type, venue, event_date, time_slot, price, status 
                            FROM bookings WHERE email=? ORDER BY event_date DESC");
    $stmt->bind_param("s", $searchEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Bookings</title>
  <style>
    body {
      background: #121212;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 85%;
      margin: 40px auto;
      text-align: center;
    }
    h2 {
      color: #00bcd4;
      margin-bottom: 10px;
    }
    .search-box {
      margin: 20px auto;
    }
    input[type="email"] {
      padding: 10px;
      width: 280px;
      border-radius: 5px;
      border: none;
      outline: none;
      font-size: 0.95rem;
    }
    button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      background: #00bcd4;
      color: #fff;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }
    button:hover {
      background: #0097a7;
    }
    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }
    th, td {
      padding: 15px;
      text-align: center;
    }
    th {
      background: #00bcd4;
      color: #fff;
      font-size: 1rem;
    }
    tr:nth-child(even) {
      background: #2a2a2a;
    }
    tr:nth-child(odd) {
      background: #1e1e1e;
    }
    td {
      font-size: 0.95rem;
    }
    .status-pending { color: orange; font-weight: bold; }
    .status-paid { color: limegreen; font-weight: bold; }
    .status-cancelled { color: red; font-weight: bold; }
    a.cancel-btn {
      color: #ff4081;
      text-decoration: none;
      font-weight: bold;
    }
    a.cancel-btn:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📅 My Bookings</h2>
    <p>Enter your email below to find your bookings:</p>

    <form method="POST" class="search-box">
      <input type="email" name="search_email" placeholder="Enter your email" required>
      <button type="submit">🔍 Search</button>
    </form>

    <?php if ($searchEmail): ?>
        <h3>Showing bookings for <strong><?= htmlspecialchars($searchEmail) ?></strong></h3>
        <?php if (count($bookings) > 0): ?>
            <table>
              <tr>
                <th>Event</th>
                <th>Venue</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              <?php foreach ($bookings as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['event_type']) ?></td>
                  <td><?= htmlspecialchars($row['venue']) ?></td>
                  <td><?= htmlspecialchars($row['event_date']) ?></td>
                  <td><?= htmlspecialchars($row['time_slot']) ?></td>
                  <td>₹<?= number_format($row['price'], 2) ?></td>
                  <td>
                    <?php
                      if ($row['status'] === 'Pending') echo "<span class='status-pending'>Pending Payment</span>";
                      elseif ($row['status'] === 'Paid' || $row['status'] === 'Confirmed') echo "<span class='status-paid'>Confirmed</span>";
                      elseif ($row['status'] === 'Cancelled') echo "<span class='status-cancelled'>Cancelled</span>";
                      else echo htmlspecialchars($row['status']);
                    ?>
                  </td>
                  <td>
                    <?php if ($row['status'] === 'Pending' || $row['status'] === 'Paid'): ?>
                      <a href="cancel.php?booking_id=<?= $row['id'] ?>" class="cancel-btn">❌ Cancel</a>
                    <?php else: ?>
                      ❌ Already Cancelled
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>⚠ No bookings found for this email.</p>
        <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>
