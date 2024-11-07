<?php

namespace App\Facades;

use App\Services\interface\AccountServiceInterface;
use Illuminate\Support\Facades\Facade;

class AccountFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return AccountServiceInterface::class;
    }
}
