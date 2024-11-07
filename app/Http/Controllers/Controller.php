<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function tryCatch(callable $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue',
                'error' => true,
                'details' => $e->getMessage()
            ], 500);
        }
    }

    protected function getPaginationParams(Request $request): array
    {
        return [
            'page' => (int) $request->query('page', 1),
            'limit' => (int) $request->query('limit', 10),
        ];
    }

    protected function createPaginatedResponse(array $result, string $message, array $params): JsonResponse
    {
        $totalPages = ceil($result['totalCount'] / $params['limit']);

        return response()->json([
            'data' => $result['data'],
            'message' => $message,
            'error' => false,
            'pagination' => [
                'currentPage' => $params['page'],
                'itemsPerPage' => $params['limit'],
                'totalItems' => $result['totalCount'],
                'totalPages' => $totalPages,
            ],
        ]);
    }

    protected function createSuccessResponse(string $message, $data): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'error' => false,
        ]);
    }

    protected function createErrorResponse(string $message): JsonResponse
    {
        return response()->json([
            'data' => null,
            'message' => $message,
            'error' => true,
        ]);
    }

    protected function getUserRequest()
    {
        return request()->user(); // Suppose que l'authentification Laravel est configurée pour attacher l'utilisateur à la requête
    }
}
