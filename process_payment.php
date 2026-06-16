<?php
include 'config.php';
session_start();

$booking_id     = $_POST['booking_id']     ?? null;
$payment_method = $_POST['payment_method'] ?? null;
$upi_app        = $_POST['upi_app']        ?? null;
$upi_id         = $_POST['upi_id']         ?? null;

$status = "error";
$message = "⚠ Required fields are missing. Please try again.";
$price = 0;

if ($booking_id && $payment_method) {
    // Fetch booking
    $stmt = $conn->prepare("SELECT price, status FROM bookings WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        // Invalid booking
        header("Location: payment_failed.php?error=invalid_booking");
        exit();
    } elseif ($row['status'] === 'Paid' || $row['status'] === 'Confirmed') {
        // Already paid, just go to success page
        header("Location: payment_success.php?booking_id=$booking_id&already_paid=1");
        exit();
    } else {
        $price = $row['price'];

        if ($payment_method !== "UPI") {
            $upi_app = null;
            $upi_id  = null;
        }

        // Update booking as Paid
        $stmt = $conn->prepare("UPDATE bookings 
                                SET status='Paid', payment_method=?, upi_app=?, upi_id=? 
                                WHERE id=?");
        $stmt->bind_param("sssi", $payment_method, $upi_app, $upi_id, $booking_id);
        if ($stmt->execute()) {
            // ✅ Redirect to detailed success page
            header("Location: payment_success.php?booking_id=$booking_id");
            exit();
        } else {
            // ❌ Database error
            header("Location: payment_failed.php?error=db_error");
            exit();
        }
        $stmt->close();
    }
} else {
    // Missing fields
    header("Location: payment_failed.php?error=missing_fields");
    exit();
}

$conn->close();
