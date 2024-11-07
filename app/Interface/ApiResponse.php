<?php

namespace App\Interface;

interface ApiResponse
{
    public function getData(): mixed; 
    public function getMessage(): string;
    public function isError(): bool;
    public function getPagination(): ?Pagination;
}
