<?php
session_start();

// if admin not logged in, redirect
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background:#121212; color:#fff; }
        .dashboard-container {
            max-width: 800px; margin: 50px auto; padding: 20px;
            background: #1e1e1e; border-radius: 10px; text-align:center;
        }
        a {
            display:inline-block; margin:10px; padding:10px 20px;
            background:#ff9800; color:#fff; text-decoration:none; border-radius:5px;
        }
        a:hover { background:#e68900; }
        .logout-btn { background: red; font-weight: bold; }
        .logout-btn:hover { background: darkred; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
        <p>This is your admin dashboard.</p>
        
        <a href="update_hotels.php">Update Hotel Prices</a>
        <a href="view_bookings.php">View Bookings</a>
        <a href="view_feedback.php">Manage Feedback</a>
        <a href="view_users.php">View Registered Users</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>
