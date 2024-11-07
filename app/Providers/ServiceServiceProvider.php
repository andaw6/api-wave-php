<?php

namespace App\Providers;

use App\Services\BillService;
use App\Services\UserService;
use App\Services\AccountService;
use App\Services\CompanyService;
use App\Services\ContactService;
use App\Services\TransactionService;
use App\Services\PersonalInfoService;
use Illuminate\Support\ServiceProvider;
use App\Services\interface\BillServiceInterface;
use App\Services\interface\UserServiceInterface;
use App\Services\CreditPurchaseTransactionService;
use App\Services\GeneratorSenegalPhoneNumerService;
use App\Services\interface\AccountServiceInterface;
use App\Services\interface\CompanyServiceInterface;
use App\Services\interface\ContactServiceInterface;
use App\Services\interface\TransactionServiceInterface;
use App\Services\interface\PersonalInfoServiceInterface;
use App\Services\interface\CreditPurchaseTransactionServiceInterface;
use App\Services\interface\GeneratorSenegalPhoneNumerServiceInterface;
use App\Services\interface\QrCodeServiceInterface;
use App\Services\QrCodeService;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AccountServiceInterface::class, AccountService::class);
        $this->app->bind(BillServiceInterface::class, BillService::class);
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);
        // $this->app->bind(CreditPurchaseTransactionServiceInterface::class, CreditPurchaseTransactionService::class);
        // $this->app->bind(PersonalInfoServiceInterface::class, PersonalInfoService::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(QrCodeServiceInterface::class, QrCodeService::class);
        $this->app->bind(GeneratorSenegalPhoneNumerServiceInterface::class, GeneratorSenegalPhoneNumerService::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
