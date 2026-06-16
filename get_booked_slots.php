<?php
include 'config.php';

// Get venue and date from request
$venue = isset($_GET['venue']) ? $_GET['venue'] : '';
$event_date = isset($_GET['event_date']) ? $_GET['event_date'] : '';

if (empty($venue) || empty($event_date)) {
    echo json_encode([]);
    exit;
}

// Query booked slots for the given venue and date
$stmt = $conn->prepare("SELECT time_slot FROM bookings WHERE venue = ? AND event_date = ?");
$stmt->bind_param("ss", $venue, $event_date);
$stmt->execute();
$result = $stmt->get_result();

$bookedSlots = [];
while ($row = $result->fetch_assoc()) {
    $bookedSlots[] = $row['time_slot']; 
    // assuming your bookings table has a `time_slot` column storing values like 'Morning', 'Afternoon', 'Evening'
}

echo json_encode($bookedSlots);

$stmt->close();
$conn->close();
?>
