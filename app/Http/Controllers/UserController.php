<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createAgentOrAdmin(Request $request): JsonResponse
    {
        return $this->userService->createAgentOrAdmin($request->all());
    }

    public function createClientByAgent(Request $request): JsonResponse
    {
        return $this->userService->createClientByAgent($request->all());
    }

    public function getUserById(string $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json(['message' => 'Utilisateur récupéré avec succès', 'data' => $user]);
    }

    public function listUsers(Request $request): JsonResponse
    {
        $pagination = $this->getPaginationParams($request);
        $filters = $request->only('role');
        $users = $this->userService->listUsers($filters, $pagination['limit'], $pagination['page']);

        $result = [
            'data' => $users->items(),
            'totalCount' => $users->total(),
        ];

        return $this->createPaginatedResponse($result, 'Liste des utilisateurs récupérée avec succès', $pagination);
    }

    public function deleteUser(string $id): JsonResponse
    {
        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }

    public function getCurrentUser(Request $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $user = Auth::user();
    
            
            if (!$user) {
                return $this->createErrorResponse('Utilisateur non trouvé');
            }

            
    
            $userData = $this->userService->getCurrentUser($user);
    
            return $this->createSuccessResponse('Utilisateur récupéré avec succès', $userData);
        });
    }
    
}
