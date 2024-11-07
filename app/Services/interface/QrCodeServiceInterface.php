<?php

namespace App\Services\interface;

interface QrCodeServiceInterface
{
    function generate(string $data): string;
}
