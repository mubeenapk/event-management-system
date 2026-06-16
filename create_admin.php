<?php
include("config.php");

// Change these
$username = "admin";
$password = "addmin123"; // your new password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update password if admin already exists
$sql = "UPDATE admin SET password='$hashed_password' WHERE username='$username'";
if (mysqli_query($conn, $sql)) {
    echo "Password updated successfully for $username.";
} else {
    echo "Error updating password: " . mysqli_error($conn);
}
?>
