<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $event_type  = $_POST['event'] ?? '';
    $venue       = $_POST['venue'] ?? '';
    $customVenue = $_POST['customVenue'] ?? '';
    $event_date  = $_POST['event_date'] ?? '';
    $time_slot   = $_POST['time_slot'] ?? '';
    $message     = $_POST['message'] ?? '';

    // Use custom venue if provided
    if (!empty($customVenue)) {
        $venue = $customVenue;
    }

    // ✅ Clean venue name: remove (Premium), (Standard), (Budget), etc.
    $venue = preg_replace("/\s*\((Premium|Standard|Budget|Low-Cost|Free)\)/i", "", $venue);

    if (empty($name) || empty($email) || empty($phone) || empty($event_type) || empty($venue) || empty($event_date) || empty($time_slot)) {
        die("⚠ Please fill all required fields.");
    }

    // ✅ Recheck slot availability
    $check = $conn->prepare("SELECT COUNT(*) FROM bookings 
                             WHERE venue=? AND event_date=? AND time_slot=? 
                             AND status IN ('Pending','Paid','Confirmed')");
    $check->bind_param("sss", $venue, $event_date, $time_slot);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        die("❌ Sorry, this slot has already been booked. Please select another.");
    }

    // ✅ Secure price lookup (server-side)
    $price = 0;
    $venueStmt = $conn->prepare("SELECT price FROM hotels WHERE hotel_name=? LIMIT 1");
    $venueStmt->bind_param("s", $venue);
    $venueStmt->execute();
    $venueStmt->bind_result($venuePrice);
    if ($venueStmt->fetch()) {
        $price = $venuePrice;
    } else {
        $price = 0; // fallback if venue not in hotels table
    }
    $venueStmt->close();

    // ✅ Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings 
        (name, email, phone, event_type, venue, event_date, time_slot, message, price, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    
    $stmt->bind_param("ssssssssd", 
        $name, $email, $phone, $event_type, $venue, $event_date, $time_slot, $message, $price
    );

    if ($stmt->execute()) {
        $booking_id = $stmt->insert_id;
        header("Location: payment.php?booking_id=$booking_id");
        exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
