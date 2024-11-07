<?php

namespace App\Facades;

use App\Services\interface\PersonalInfoServiceInterface;
use Illuminate\Support\Facades\Facade;

class PersonalInfoFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return PersonalInfoServiceInterface::class;
    }
}
