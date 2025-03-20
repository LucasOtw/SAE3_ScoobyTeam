<?php
require_once __DIR__ . '/../vendor/autoload.php';

use BaconQrCode\Renderer\Image\PngImageBackEnd;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\ImageRenderer;
use BaconQrCode\Renderer\Image\RendererStyle\RendererStyle;

$writer = new Writer(
    new ImageRenderer(
        new RendererStyle(300), 
        new PngImageBackEnd()
    )
);

$qrCode = $writer->writeString('https://www.example.com');

header('Content-Type: image/png');
echo $qrCode;
?>
