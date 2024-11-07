<?php

namespace App\Facades;

use App\Services\interface\CreditPurchaseTransactionServiceInterface;
use Illuminate\Support\Facades\Facade;

class CreditPurchaseFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return CreditPurchaseTransactionServiceInterface::class;
    }
}
