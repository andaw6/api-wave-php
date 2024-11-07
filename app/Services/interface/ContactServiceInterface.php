<?php

namespace App\Services\interface;

interface ContactServiceInterface
{
    public function toggleFavorite(string $id);
    public function getById(string $id);
    public function createContact(array $data);
    public function getAll($page = 1, $limit = 10);
    public function getByPhone(string $phone);
    public function getByUser(string $userId, $page = 1, $limit = 10);
}
