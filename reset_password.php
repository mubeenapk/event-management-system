<?php
include 'config.php';
session_start();

$message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id, reset_expiry FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($user_id, $expiry);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id || strtotime($expiry) < time()) {
        die("<p style='color:red;text-align:center;'>❌ Invalid or expired reset link.</p>");
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expiry=NULL WHERE id=?");
        $stmt->bind_param("si", $new_password, $user_id);
        $stmt->execute();
        $stmt->close();

        $message = "<span style='color:lime;'>✅ Password has been reset. <a href='auth.php' style='color:#ffcc00;'>Login</a></span>";
    }
} else {
    die("<p style='color:red;text-align:center;'>❌ Invalid request.</p>");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Password | ivento</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <h1 class="brand"><span class="iven">iven</span><span class="to">to</span></h1>
    <h2>Reset Password</h2>
    <?php if (!empty($message)): ?>
      <p><?= $message ?></p>
    <?php else: ?>
      <form method="POST" class="auth-form">
        <input type="password" name="password" placeholder="New Password" required><br>
        <button type="submit">Reset Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
