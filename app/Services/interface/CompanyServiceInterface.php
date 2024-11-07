<?php

namespace App\Services\interface;

interface CompanyServiceInterface
{
    public function getById(string $id);
    public function getAll($page = 1, $limit = 50);
}
