<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\interface\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    public function createAgentOrAdmin(array $data): JsonResponse
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:ADMIN,AGENT',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(['message' => 'Utilisateur créé avec succès', 'data' => $user], 201);
    }

    /**
     * Create a client by an agent.
     */
    public function createClientByAgent(array $data): JsonResponse
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phoneNumber' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phoneNumber' => $data['phoneNumber'],
            'role' => User::ROLE_CLIENT,
            'password' => Hash::make($data['password'] ?? '0000'),
        ]);

        return response()->json(['message' => 'Client créé avec succès par un agent', 'data' => $user], 201);
    }

    /**
     * Retrieve a user by ID.
     */
    public function getUserById(string $id): ?User
    {
        return User::find($id);
    }

    /**
     * List users with optional role filter and pagination.
     */
    public function listUsers(array $filters, int $limit, int $page): LengthAwarePaginator
    {
        $query = User::query();

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Delete a user by ID.
     */
    public function deleteUser(string $id): bool
    {
        $user = User::find($id);
        
        if ($user) {
            $user->delete();
            return true;
        }

        return false;
    }

    /**
     * Get the current authenticated user with related data.
     */
    public function getCurrentUser(User $user): ?User
    {
        
        return User::with([
            'transactions' => function($query) {
                $query->with(['sender', 'receiver', 'creditPurchase']);
            },
            'receivedTransactions' => function($query) {
                $query->with(['sender', 'receiver', 'creditPurchase']);
            },
            'bills' => function($query) {
                $query->with(['company', 'user']);
            },
            'account',
            'contacts',
            'personalInfo'
        ])
        ->find((string) $user->id);
    }
}
