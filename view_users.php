<?php
session_start();
include 'config.php';

// ✅ Ensure only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$result = $conn->query("SELECT id, username, email, last_login, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Users - Admin</title>
  <style>
    body { font-family: Arial, sans-serif; background:#121212; color:#fff; margin:20px; }
    h2 { text-align:center; color:#ffcc00; }
    table { width:100%; border-collapse: collapse; margin-top:20px; background:#1e1e1e; border-radius:8px; overflow:hidden; }
    th, td { padding:12px; text-align:left; border-bottom:1px solid #333; }
    th { background:#333; }
    tr:hover { background:#222; }
    .back-btn {
      display:inline-block; margin-top:20px; padding:10px 15px;
      background:#ff4081; color:#fff; text-decoration:none; border-radius:6px;
    }
    .back-btn:hover { background:#e73370; }
  </style>
</head>
<body>
  <h2>Registered Users</h2>
  <p style="text-align:center;">Welcome, <?= htmlspecialchars($_SESSION['admin_username']); ?> 👋</p>

  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Last Login</th>
      <th>Registered On</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= $row['last_login'] ?? 'Never' ?></td>
        <td><?= $row['created_at'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div style="text-align:center;">
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
<?php $conn->close(); ?>
