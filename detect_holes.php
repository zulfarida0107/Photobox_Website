<?php
$path = "public/uploads/frames/frame_cuitbox_beats_code_vol_01_4.png";
if (!file_exists($path)) {
    die("File not found\n");
}

$img = imagecreatefrompng($path);
$width = imagesx($img);
$height = imagesy($img);

echo "Image dimensions: {$width}x{$height}\n";

// Let's sample the image to find transparent blocks (holes).
// We'll group adjacent transparent pixels.
$visited = array_fill(0, $width, array_fill(0, $height, false));
$holes = [];

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        if ($visited[$x][$y]) continue;
        
        $rgba = imagecolorat($img, $x, $y);
        $alpha = ($rgba & 0x7F000000) >> 24; // 127 is fully transparent, 0 is fully opaque
        
        // Let's check if it is transparent (alpha > 64 or so)
        if ($alpha > 60) {
            // Find boundary of this transparent region using BFS
            $minX = $x; $maxX = $x;
            $minY = $y; $maxY = $y;
            
            $queue = [[$x, $y]];
            $visited[$x][$y] = true;
            
            while (!empty($queue)) {
                list($cx, $cy) = array_shift($queue);
                
                if ($cx < $minX) $minX = $cx;
                if ($cx > $maxX) $maxX = $cx;
                if ($cy < $minY) $minY = $cy;
                if ($cy > $maxY) $maxY = $cy;
                
                // Check 4 neighbors
                $neighbors = [
                    [$cx + 1, $cy],
                    [$cx - 1, $cy],
                    [$cx, $cy + 1],
                    [$cx, $cy - 1]
                ];
                
                foreach ($neighbors as $n) {
                    $nx = $n[0];
                    $ny = $n[1];
                    
                    if ($nx >= 0 && $nx < $width && $ny >= 0 && $ny < $height) {
                        if (!$visited[$nx][$ny]) {
                            $nrgba = imagecolorat($img, $nx, $ny);
                            $nalpha = ($nrgba & 0x7F000000) >> 24;
                            if ($nalpha > 60) {
                                $visited[$nx][$ny] = true;
                                $queue[] = [$nx, $ny];
                            }
                        }
                    }
                }
            }
            
            $w = $maxX - $minX + 1;
            $h = $maxY - $minY + 1;
            
            // Filter out tiny transparent artifacts (less than 10x10 px)
            if ($w > 10 && $h > 10) {
                $holes[] = [
                    'minX' => $minX,
                    'maxX' => $maxX,
                    'minY' => $minY,
                    'maxY' => $maxY,
                    'w' => $w,
                    'h' => $h
                ];
            }
        }
    }
}

echo "Detected " . count($holes) . " transparent holes:\n";
foreach ($holes as $idx => $hole) {
    // Let's also calculate the coordinates scaled to a 1200x1800 design basis
    $scaleX = 1200 / $width;
    $scaleY = 1800 / $height;
    
    $scaledX = round($hole['minX'] * $scaleX);
    $scaledY = round($hole['minY'] * $scaleY);
    $scaledW = round($hole['w'] * $scaleX);
    $scaledH = round($hole['h'] * $scaleY);
    
    echo "Hole " . ($idx + 1) . ":\n";
    echo "  Original: x={$hole['minX']}, y={$hole['minY']}, w={$hole['w']}, h={$hole['h']}\n";
    echo "  Scaled (1200x1800 basis): x={$scaledX}, y={$scaledY}, w={$scaledW}, h={$scaledH}\n";
}

imagedestroy($img);
