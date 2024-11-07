<?php

namespace App\Facades;

use App\Services\interface\TransactionServiceInterface;
use Illuminate\Support\Facades\Facade;

class TransactionFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return TransactionServiceInterface::class;
    }
}
