<?php
$conn = mysqli_connect("localhost", "root", "", "photobox");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$result = mysqli_query($conn, "SELECT b.id as booking_id, b.status as booking_status, s.id as session_id, s.email_recipient FROM bookings b LEFT JOIN sessions s ON b.id = s.booking_id ORDER BY b.created_at DESC LIMIT 5");
while ($row = mysqli_fetch_assoc($result)) {
    echo "Booking ID: {$row['booking_id']} | Status: {$row['booking_status']} | Session ID: {$row['session_id']} | Email: {$row['email_recipient']}\n";
}
mysqli_close($conn);


