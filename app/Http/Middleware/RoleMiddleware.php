<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  /**
     * Les rôles valides
     */
    protected $rolesValides = ['ADMIN', 'AGENT', 'CLIENT', 'VENDOR'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Filtrer les rôles selon ceux qui sont valides
        $filteredRoles = array_intersect($roles, $this->rolesValides);

        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check() || !Auth::user()->role) {
            return response()->json([
                'message' => 'Accès refusé. Utilisateur non authentifié.',
                'error' => true,
                'data' => null
            ], 403);
        }

        // Récupérer le rôle de l'utilisateur
        $userRole = Auth::user()->role;

        // Vérifier si le rôle de l'utilisateur est dans les rôles autorisés
        if (!in_array($userRole, $filteredRoles)) {
            return response()->json([
                'message' => 'Accès refusé. Vous n\'avez pas les autorisations nécessaires.',
                'error' => true,
                'data' => null
            ], 403);
        }

        return $next($request);
    }
}
