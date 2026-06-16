<?php
session_start();
include("config.php"); // database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch the admin record securely
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            // ✅ Store correct session values
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid username or password";
        }
    } else {
        $error = "❌ Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background:#121212; color:#fff; }
        .login-container {
            max-width: 400px; margin: 100px auto; padding: 20px;
            background: #1e1e1e; border-radius: 10px; text-align:center;
        }
        input { width: 90%; padding:10px; margin:10px 0; border-radius:5px; border:none; }
        button { padding:10px 20px; border:none; border-radius:5px; background:#ff9800; color:#fff; cursor:pointer; }
        button:hover { background:#e68900; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Username" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
