<?php

namespace App\Interface;

interface TimeFrameConfig
{
    public function getUnit(): string; // 'date' ou 'month'
    public function getValue(): int;
}
