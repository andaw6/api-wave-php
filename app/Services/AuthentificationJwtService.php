<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\interface\AuthentificationServiceInterface;

class AuthentificationJwtService implements AuthentificationServiceInterface
{
    public function authentificate(array $credentials)
    {
        // Ajustez les credentials pour utiliser le numéro de téléphone
        $credentials["phoneNumber"] = $credentials["phone"];
        unset($credentials["phone"]);

        // Authentification de l'utilisateur
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'phone' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        $user = Auth::user();

        // Génération du token avec une payload personnalisée
        $customClaims = [
            'userId' => $user->id,
            'phone_number' => $user->phoneNumber,
            'role' => $user->role,
        ];

        $token = JWTAuth::claims($customClaims)->fromUser($user);

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'role' => $user->role,
                'phoneNumber' => $user->phoneNumber,
            ],
        ];
    }

    public function logout($user)
    {
        // Invalider le token JWT
        JWTAuth::invalidate(JWTAuth::getToken());
        Auth::logout();
        
        return ['message' => 'Successfully logged out'];
    }
}
