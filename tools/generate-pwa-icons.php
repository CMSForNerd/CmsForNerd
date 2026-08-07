<?php

/**
 * PWA Icon Generator
 * Creates simple placeholder icons for the PWA manifest.
 */

$sizes = [192, 512];
$dir = __DIR__ . '/../assets/pwa';

if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

foreach ($sizes as $size) {
    $img = imagecreatetruecolor($size, $size);

    // Background: Bootstrap Primary Blue (#0d6efd)
    $bg = imagecolorallocate($img, 13, 110, 253);
    imagefill($img, 0, 0, $bg);

    // Text: White (#ffffff)
    $text = imagecolorallocate($img, 255, 255, 255);

    // Simple text in the center
    $string = "Nerd";
    $fontSize = $size > 200 ? 5 : 4;
    $fontWidth = imagefontwidth($fontSize) * strlen($string);
    $fontHeight = imagefontheight($fontSize);

    $x = (int) (($size - $fontWidth) / 2);
    $y = (int) (($size - $fontHeight) / 2);

    imagestring($img, $fontSize, $x, $y, $string, $text);

    // Add text "v4.3.0" below
    $string2 = "v4.3.0";
    $fontWidth2 = imagefontwidth($fontSize) * strlen($string2);
    $x2 = (int) (($size - $fontWidth2) / 2);
    $y2 = $y + $fontHeight + 5;

    imagestring($img, $fontSize, $x2, $y2, $string2, $text);

    $filename = $dir . "/icon-{$size}x{$size}.png";
    imagepng($img, $filename);
    imagedestroy($img);

    echo "Created: $filename\n";
}
