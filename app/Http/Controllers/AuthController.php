<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Services\interface\AuthentificationServiceInterface;


class AuthController extends Controller
{

    protected $authService;


    public function __construct(AuthentificationServiceInterface $authService)
    {
        $this->authService = $authService;
    }



    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $token = $this->authService->authentificate($data)["token"];
        return [
            "data" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            "message" => "Connexion réussie"
        ];
    }


    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return ["message" => "Déconnexion réusie"];
    }
}