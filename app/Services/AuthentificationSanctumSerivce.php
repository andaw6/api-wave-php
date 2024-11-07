<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\interface\AuthentificationServiceInterface;

class AuthentificationSanctumSerivce implements AuthentificationServiceInterface
{
    // public function authentificate(array $credentials)
    // {
    //     $credentials["phoneNumber"] = $credentials["phone"];
    //     unset($credentials["phone"]);
    //     // dd($credentials);
    //     if (!Auth::attempt($credentials)) {
    //         throw ValidationException::withMessages([
    //             'phone' => ['Les informations d\'identification fournies sont incorrectes.'],
    //         ]);
    //     }

    //     $user = User::where('phoneNumber', $credentials['phoneNumber'])->firstOrFail();

    //     // Créer le token avec des informations supplémentaires
    //     $token = $user->createToken('auth_token', ['user:id,role,phoneNumber'])->plainTextToken;

    //     return [
    //         'token' => $token,
    //         'user' => [
    //             'id' => $user->id,
    //             'role' => $user->role,
    //             'phoneNumber' => $user->phoneNumber,
    //         ],
    //     ];
    // }

    public function authentificate(array $credentials)
    {
        $credentials["phoneNumber"] = $credentials["phone"];
        unset($credentials["phone"]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'phone' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        $user = User::where('phoneNumber', $credentials['phoneNumber'])->firstOrFail();

        // Créer le token avec les informations supplémentaires
        $token = $user->createToken('auth_token', [
            'user_id' => $user->id,
            'user_role' => $user->role
        ])->plainTextToken;

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
        $user->tokens()->delete();
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return ['message' => 'Successfully logged out'];
    }
}
