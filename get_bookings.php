<?php
include 'config.php';

$month = $_GET['month'] ?? date('m');
$year  = $_GET['year'] ?? date('Y');

$stmt = $conn->prepare("SELECT event_date, time_slot FROM bookings WHERE MONTH(event_date) = ? AND YEAR(event_date) = ?");
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $date = $row['event_date'];
    $slot = $row['time_slot'];
    if (!isset($bookings[$date])) {
        $bookings[$date] = ["Morning" => false, "Afternoon" => false, "Evening" => false];
    }
    $bookings[$date][$slot] = true;
}
echo json_encode($bookings);
