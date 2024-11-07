<?php

namespace App\Interface;

interface PaginationResult
{
    public function getData(): array; // Un tableau de données
    public function getTotalCount(): int; 
}
