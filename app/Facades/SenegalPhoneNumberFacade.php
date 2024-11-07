<?php

namespace App\Facades;

use App\Services\interface\GeneratorSenegalPhoneNumerServiceInterface;
use Illuminate\Support\Facades\Facade;

class SenegalPhoneNumberFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return GeneratorSenegalPhoneNumerServiceInterface::class;
    }
}
