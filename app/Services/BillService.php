<?php

namespace App\Services;

use Exception;
use App\Models\Bill;
use App\Services\Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\interface\BillServiceInterface;
use App\Services\interface\UserServiceInterface;
use App\Services\interface\AccountServiceInterface;
use App\Services\interface\CompanyServiceInterface;

class BillService extends Service implements BillServiceInterface
{

    protected UserServiceInterface $userService;
    protected CompanyServiceInterface $companyService;
    protected AccountServiceInterface $accountService;

    public function __construct(UserServiceInterface $userService, CompanyServiceInterface $companyService, AccountServiceInterface $accountService)
    {
        $this->userService = $userService;
        $this->companyService = $companyService;
        $this->accountService = $accountService;
    }

    public function getAll($timeFrame = null, $page = 1, $limit = 50)
    {
        $query = Bill::with(["company"]);
        $this->applyTimeFrameFilter($query, $timeFrame);
        $data = $query->paginate($limit, ['*'], 'page', $page);
        return [
            'data' => $data->items(),
            ...$this->getPagination($data)
        ];
    }

    public function getAllByUser(string $userId, $timeFrame = null, $page = 1, $limit = 10)
    {
        $query = Bill::where("userId", $userId);        
        $this->applyTimeFrameFilter($query, $timeFrame);    
        $query->with(["company"]);
        $data = $query->paginate($limit, ['*'], 'page', $page);
        return [
            'data' => $data->items(),
            ...$this->getPagination($data)
        ];
    }

    public function getById(string $id)
    {
        if (!Str::isUuid($id)) {
            throw new \Exception('Format UUID invalide pour id');
        }

        $bill = Bill::find($id);
        $bill->load(["company"]);
        return $bill;
    }



    public function createBill(string $userId, float $amount, string $companyId)
    {
        $blockedRoles = ['ADMIN', 'AGENT'];

        $user = $this->userService->getUserById($userId, ['account' => true]);
        if (!$user) {
            throw new Exception("L'utilisateur n'existe pas.");
        }

        $company = $this->companyService->getById($companyId);
        if (!$company) {
            throw new Exception("La compagnie n'existe pas.");
        }

        if (in_array($user->role, $blockedRoles)) {
            throw new Exception('Ce numéro ne peut pas recevoir de transaction');
        }

        $account = $this->accountService->getAccountByUser($user->id);
        if ($account->balance < $amount) {
            throw new Exception('Le solde est insuffisant');
        }

        return DB::transaction(function () use ($user, $amount, $userId, $companyId, $account) {
            $this->accountService->debit($user->phoneNumber, $amount);

            $bill = Bill::create([
                'userId' => $userId,
                'companyId' => $companyId,
                'amount' => $amount,
                'currency' => $account->currency,
                'status' => Bill::STATUS_PAID,
            ]);
            $bill->load(["company"]);
            return $bill;
        });
    }
}
