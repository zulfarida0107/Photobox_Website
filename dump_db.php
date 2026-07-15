<?php
$conn = mysqli_connect("localhost", "root", "", "photobox");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$result = mysqli_query($conn, "SELECT * FROM frames");
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: {$row['id']} | Name: {$row['name']} | Count: {$row['photo_count']} | Path: {$row['file_path']}\n";
}
mysqli_close($conn);
