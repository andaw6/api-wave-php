<?php

namespace App\Interface;

interface Pagination
{
    public function getCurrentPage(): int;
    public function getItemsPerPage(): int;
    public function getTotalItems(): int;
    public function getTotalPages(): int;
}
