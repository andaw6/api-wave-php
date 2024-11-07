<?php

namespace App\Interface;

interface IJwtPayload
{
    public function getUserId(): string;
    public function getRole(): string;
}
