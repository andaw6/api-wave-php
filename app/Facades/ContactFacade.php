<?php

namespace App\Facades;

use App\Services\interface\ContactServiceInterface;
use Illuminate\Support\Facades\Facade;

class ContactFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return ContactServiceInterface::class;
    }
}
