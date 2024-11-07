<?php

namespace App\Services\interface;

interface AuthentificationServiceInterface
{
    public function authentificate(array $credentials);

    public function logout($user);

    // public function refreshToken($refreshToken);
}
