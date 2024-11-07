<?php

namespace App\Facades;

use App\Services\interface\QrCodeServiceInterface;
use Illuminate\Support\Facades\Facade;

class QrcodeFacade extends Facade
{

    protected static function getFacadeAccessor(){
        return QrCodeServiceInterface::class;
    }
}
