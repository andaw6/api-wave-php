<?php

namespace App\Interface;

interface PaginationParams
{
    public function getPage(): int;
    public function getLimit(): int;
}
