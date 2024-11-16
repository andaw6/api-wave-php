<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Services\interface\QrCodeServiceInterface;

class QrCodeService implements QrCodeServiceInterface
{
    public function generate(string $data): string
    {
        // Crée une instance de QrCode
        $qrCode = new QrCode($data);
        $writer = new PngWriter();

        // Génère le QR code et obtient l'image au format PNG
        $result = $writer->write($qrCode);

        // Convertit le QR code en base64 pour le retourner en tant que chaîne
        return base64_encode($result->getString());
    }
    
}
