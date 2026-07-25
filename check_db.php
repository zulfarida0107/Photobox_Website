<?php
$conn = @mysqli_connect("127.0.0.1", "root", "", "photobox", 3306);
if (!$conn) {
    $conn = @mysqli_connect("localhost", "root", "", "photobox", 3306);
}
if (!$conn) {
    die("Connect error: " . mysqli_connect_error() . "\n");
}

$res = mysqli_query($conn, "
    SELECT f.id, f.name, f.file_path, f.layout_id, l.name as layout_name, l.photo_count, l.slot_coordinates 
    FROM frames f 
    LEFT JOIN frame_layouts l ON l.id = f.layout_id
");

echo "=== DATABASE FRAMES & LAYOUTS ===\n";
while ($r = mysqli_fetch_assoc($res)) {
    echo "FRAME ID: {$r['id']} | Name: {$r['name']} | File: {$r['file_path']}\n";
    echo "  -> Layout ID: {$r['layout_id']} | Layout Name: {$r['layout_name']} | Photo Count: {$r['photo_count']}\n";
    echo "  -> Slot Coords: {$r['slot_coordinates']}\n\n";
}
mysqli_close($conn);
