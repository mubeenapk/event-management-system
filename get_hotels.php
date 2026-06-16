<?php
include("config.php");

header("Content-Type: application/json");

$sql = "SELECT id, hotel_name, price, category FROM hotels";
$result = mysqli_query($conn, $sql);

$hotels = [];
while ($row = mysqli_fetch_assoc($result)) {
    $hotels[] = $row;
}

echo json_encode($hotels);
?>
