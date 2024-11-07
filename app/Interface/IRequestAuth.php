<?php

namespace App\Interface;

use Illuminate\Http\Request;

interface IRequestAuth extends Request {
    public function getUser(): IJwtPayload; // S'assurer que la propriété utilisateur est définie ici
}