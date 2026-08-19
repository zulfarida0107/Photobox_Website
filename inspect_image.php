<?php
$path = "public/uploads/frames/frame_cuitbox_kawaii_love_heart_6.png";
if (file_exists($path)) {
    $info = getimagesize($path);
    echo "Width: {$info[0]}, Height: {$info[1]}, Mime: {$info['mime']}\n";
} else {
    echo "File not found at $path\n";
}
