<?php

namespace App\Interface;

interface HashServiceInterface
{
    public function hash(string $password): string; // Renvoie une promesse de chaîne
    public function compare(string $password, string $hashedPassword): bool;
}
