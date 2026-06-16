<?php
include 'config.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt->close();
        $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_expiry=? WHERE email=?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        $reset_link = "http://localhost/ivento/reset_password.php?token=$token";
        $message = "<span style='color:lime;'>✅ Reset link: <a href='$reset_link' style='color:#ffcc00;'>$reset_link</a></span>";
    } else {
        $message = "<span style='color:red;'>❌ No account found with that email.</span>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password | ivento</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body class="auth-body">
  <div class="auth-container">
    <h1 class="brand"><span class="iven">iven</span><span class="to">to</span></h1>
    <h2>Forgot Password</h2>
    <form method="POST" class="auth-form">
      <input type="email" name="email" placeholder="Enter your email" required><br>
      <button type="submit">Send Reset Link</button>
    </form>
    <p style="margin-top:10px;"><?= $message ?></p>
    <p><a href="auth.php" style="color:#ff4081;">⬅ Back to Login</a></p>
  </div>
</body>
</html>
