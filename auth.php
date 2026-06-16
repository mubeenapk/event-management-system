<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
session_start();

$message = "";

// Handle Registration
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    try {
        $stmt->execute();
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['user_email'] = $email;

        header("Location: home.php?registered=1");
        exit();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $message = "⚠ This email is already registered. Please log in instead.";
        } else {
            $message = "Database error: " . $e->getMessage();
        }
    }
    $stmt->close();
}

// Handle Login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashedPassword);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['user_email'] = $email;

            $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id=?");
            $update->bind_param("i", $id);
            $update->execute();
            $update->close();

            header("Location: home.php");
            exit();
        } else {
            $message = "❌ Invalid password.";
        }
    } else {
        $message = "❌ No account found with that email.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ivento | Login & Register</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body class="auth-body">

<div class="auth-container">
    <h1 class="brand"><span class="iven">iven</span><span class="to">to</span></h1>
    
    <?php if (!empty($message)): ?>
      <p style="color:red; text-align:center;"><?= $message ?></p>
    <?php endif; ?>

    <div class="tabs">
        <button onclick="showForm('login')" id="loginTab" class="active">Login</button>
        <button onclick="showForm('register')" id="registerTab">Register</button>
    </div>

    <!-- Login Form -->
    <form id="loginForm" method="POST" class="auth-form">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>

    

    <!-- Register Form -->
    <form id="registerForm" method="POST" class="auth-form" style="display:none;">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="register">Sign Up</button>
    </form>
</div>

<script>
function showForm(type) {
    document.getElementById("loginForm").style.display = (type === 'login') ? "block" : "none";
    document.getElementById("registerForm").style.display = (type === 'register') ? "block" : "none";

    document.getElementById("loginTab").classList.toggle("active", type === 'login');
    document.getElementById("registerTab").classList.toggle("active", type === 'register');
}
</script>

</body>
</html>
