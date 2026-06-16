<?php
include 'config.php';
session_start();

// Only admins can view
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$stmt = $conn->query("SELECT id, name, email, event_type, venue, event_date, time_slot, price, status 
                      FROM bookings ORDER BY event_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Bookings - Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #121212;
      color: #fff;
      margin: 0;
      padding: 0;
    }
    h2 {
      text-align: center;
      margin: 30px 0;
      color: #ff9800;
    }
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }
    th, td {
      padding: 12px 15px;
      text-align: center;
    }
    th {
      background: #ff9800;
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

    /* Back Button */
    .back-btn {
      display:inline-block; 
      margin: 20px auto; 
      padding:10px 15px;
      background:#ff4081; 
      color:#fff; 
      text-decoration:none; 
      border-radius:6px;
      text-align:center;
    }
    .back-btn:hover { background:#e73370; }
    .back-container { text-align:center; }
  </style>
</head>
<body>
  <h2>📋 All Bookings (Admin Panel)</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Event</th>
      <th>Venue</th>
      <th>Date</th>
      <th>Time Slot</th>
      <th>Price</th>
      <th>Status</th>
    </tr>
    <?php while ($row = $stmt->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['event_type']) ?></td>
        <td><?= htmlspecialchars($row['venue']) ?></td>
        <td><?= htmlspecialchars($row['event_date']) ?></td>
        <td><?= htmlspecialchars($row['time_slot']) ?></td>
        <td>₹<?= number_format($row['price'], 2) ?></td>
        <td>
          <?php 
            if ($row['status'] === 'Pending') echo "<span class='status-pending'>Pending</span>";
            elseif ($row['status'] === 'Paid' || $row['status'] === 'Confirmed') echo "<span class='status-paid'>Confirmed</span>";
            elseif ($row['status'] === 'Cancelled') echo "<span class='status-cancelled'>Cancelled</span>";
            else echo htmlspecialchars($row['status']);
          ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div class="back-container">
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
