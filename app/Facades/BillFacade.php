<?php

namespace App\Facades;

use App\Services\interface\BillServiceInterface;
use Illuminate\Support\Facades\Facade;

class BillFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return BillServiceInterface::class;
    }
}
