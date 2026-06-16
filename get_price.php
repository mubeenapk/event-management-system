<?php
include 'config.php';

$venue = $_GET['venue'] ?? '';

if ($venue) {
    // Normalize: remove "(Premium)" "(Standard)" etc. and trim spaces
    $venue = preg_replace('/\s*\(.*?\)/', '', strtolower(trim($venue)));

    $stmt = $conn->prepare("SELECT price FROM hotels WHERE LOWER(hotel_name) = ? LIMIT 1");
    $stmt->bind_param("s", $venue);
    $stmt->execute();
    $stmt->bind_result($price);
    if ($stmt->fetch()) {
        echo json_encode(["price" => $price]);
    } else {
        echo json_encode(["price" => 0]);
    }
    $stmt->close();
}

$conn->close();
?>
