<?php
require 'vendor/autoload.php';
$db = \Config\Database::connect();
$filters = $db->query('SELECT * FROM filters')->getResultArray();
echo "FILTERS: " . count($filters) . "\n";
print_r($filters);
