<?php

namespace App\Services\interface;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
  public function createAgentOrAdmin(array $data): JsonResponse;

    public function createClientByAgent(array $data): JsonResponse;

    public function getUserById(string $id): ?User;

    public function listUsers(array $filters, int $limit, int $page): LengthAwarePaginator;

    public function deleteUser(string $id): bool;

    public function getCurrentUser(User $user): ?User;
}
