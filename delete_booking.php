<?php
include 'config.php';

$booking_id = $_GET['booking_id'] ?? 0;

if ($booking_id) {
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        echo "<h2 style='color:orange; text-align:center;'>Booking Deleted</h2>";
        echo "<p style='text-align:center;'>Your booking has been removed. Refund (if paid) will be processed manually.</p>";
        echo "<p style='text-align:center;'><a href='my_bookings.php'>Back to My Bookings</a></p>";
    } else {
        echo "❌ Error deleting booking.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
