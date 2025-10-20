<?php

namespace App\Traits\Base;

trait CodeImage
{
    public function createBarcode(string $text)
    {
        $barcodeGenerator = new \Milon\Barcode\DNS1D();

        $htmlCode = $barcodeGenerator->getBarcodeHTML($text, 'C128', 1, 40, 'black', true);

        return str_replace(
            'font-size: 1px;', // Search this style
            'font-size: 11px;', // Replace with the desired font size
            $htmlCode,
        );
    }

    public function createQRCode(string $text)
    {
        $barcodeGenerator = new \Milon\Barcode\DNS2D();

        $htmlCode = $barcodeGenerator->getBarcodeHTML($text, 'QRCODE', 3, 3, 'black', true);

        return str_replace(
            'font-size: 1px;', // Search this style
            'font-size: 11px;', // Replace with the desired font size
            $htmlCode,
        );
    }
}
