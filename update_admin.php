<?php
include("config.php");

// Change this to your existing admin username
$username = "admin";

// New password you want to set
$new_password = "addmin123";

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the password
$sql = "UPDATE admin SET password='$hashed_password' WHERE username='$username'";
if (mysqli_query($conn, $sql)) {
    echo "✅ Password updated successfully for '$username'.";
} else {
    echo "❌ Error updating password: " . mysqli_error($conn);
}
?>
