<?php

namespace App\Facades;

use App\Services\interface\CompanyServiceInterface;
use Illuminate\Support\Facades\Facade;

class CompanyFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return CompanyServiceInterface::class;
    }
}
