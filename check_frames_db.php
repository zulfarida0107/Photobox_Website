<?php
require 'vendor/autoload.php';

// Load CodeIgniter framework
$app = require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$frames = $db->table('frames')
    ->select('frames.id, frames.name, frames.file_path, frames.layout_id, frame_layouts.name as layout_name')
    ->join('frame_layouts', 'frame_layouts.id = frames.layout_id', 'left')
    ->get()
    ->getResultArray();

echo "=== ALL FRAMES IN DATABASE ===\n";
foreach ($frames as $f) {
    echo "ID: {$f['id']} | Name: {$f['name']} | Layout: {$f['layout_id']} ({$f['layout_name']}) | File: {$f['file_path']}\n";
}
