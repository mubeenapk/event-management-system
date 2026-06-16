<?php
session_start();
include("config.php");

// ✅ Check admin login correctly
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle price update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel_id = $_POST['hotel_id'];
    $new_price = $_POST['new_price'];

    $query = "UPDATE hotels SET price='$new_price' WHERE id='$hotel_id'";
    if (mysqli_query($conn, $query)) {
        $message = "Price updated successfully!";
    } else {
        $message = "Error updating price: " . mysqli_error($conn);
    }
}

// Fetch hotels
$hotels = mysqli_query($conn, "SELECT * FROM hotels");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Hotel Prices</title>
    <style>
        body { font-family: Arial, sans-serif; background:#121212; color:#fff; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background:#1e1e1e; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #333; text-align: center; }
        input[type="number"] { width: 80px; padding: 5px; }
        button { padding:5px 10px; border:none; border-radius:5px; background:#ff9800; color:#fff; cursor:pointer; }
        button:hover { background:#e68900; }
        .msg { color: lightgreen; margin:10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Hotel Prices</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
        
        <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

        <table>
            <tr>
                <th>Hotel</th>
                <th>Current Price</th>
                <th>New Price</th>
                <th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($hotels)): ?>
            <tr>
                <form method="post">
                    <td><?php echo $row['hotel_name']; ?></td>
                    <td>₹<?php echo $row['price']; ?></td>
                    <td><input type="number" step="0.01" name="new_price" required></td>
                    <td>
                        <input type="hidden" name="hotel_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Update</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>

        <p><a href="admin_dashboard.php" style="color:#ff9800;">⬅ Back to Dashboard</a></p>
    </div>
</body>
</html>
