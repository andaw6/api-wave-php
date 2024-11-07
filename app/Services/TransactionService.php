<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\User;
use App\Services\Service;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\CreditPurchaseTransaction;
use App\Services\interface\AccountServiceInterface;
use App\Services\interface\TransactionServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionService extends Service implements TransactionServiceInterface
{
    private AccountServiceInterface $accountService;

    function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getTransactionById(string $id)
    {
        $query = Transaction::where('id', $id);
        $query->with(['sender', 'receiver']);
        return $query->first();
    }

    public function getAll($timeFrame = null, $page = 1, $limit = 10, $include = null)
    {
        $query = Transaction::query();

        $this->applyTimeFrameFilter($query, $timeFrame);

        if ($include) {
            $query->with(is_array($include) ? $include : [$include]);
        }

        $data =  $query->paginate($limit, ['*'], 'page', $page);

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

        return Bill::with("company")->find($id);
    }

    public function getAllByUser(string $userId, $timeFrame = null, $page = 1, $limit = 10, $include = null)
    {
        $query = Transaction::where('senderId', $userId)
            ->orWhere('receiverId', $userId);

        $this->applyTimeFrameFilter($query, $timeFrame);

        if ($include) {
            $query->with(is_array($include) ? $include : [$include]);
        }

        $data = $query->paginate($limit, ['*'], 'page', $page);

        return [
            'data' => $data->items(),
            ...$this->getPagination($data)
        ];
    }


    public function createTransaction(
        string $senderPhoneNumber,
        string $receiverPhoneNumber,
        float $amount,
        float $feeAmount,
        string $currency,
        string $transactionType
    ) {
        $blockedRoles = [User::ROLE_ADMIN, User::ROLE_AGENT];

        // Valider les comptes de l'expéditeur et du destinataire
        $accounts = $this->accountService->validateAccounts($senderPhoneNumber, $receiverPhoneNumber);

        $senderAccount = $accounts['senderAccount'];
        $receiverAccount = $accounts['receiverAccount'];

        if (!$senderAccount || !$receiverAccount) {
            throw new \Exception("Compte de l'expéditeur ou du destinataire n'existe pas.");
        }

        $senderRole = $senderAccount->user->role;
        $receiverRole = $receiverAccount->user->role;


        if (in_array($senderRole, $blockedRoles) || in_array($receiverRole, $blockedRoles)) {
            throw new \Exception('Ce numéro ne peut pas recevoir de transaction');
        }

        if ($transactionType === Transaction::TYPE_TRANSFER && $senderAccount->balance < $amount + $feeAmount) {
            throw new \Exception('Le solde est insuffisant');
        }

        return DB::transaction(function () use (
            $transactionType,
            $senderPhoneNumber,
            $receiverPhoneNumber,
            $amount,
            $feeAmount,
            $currency,
            $senderAccount,
            $receiverAccount,
        ) {
            if ($transactionType === Transaction::TYPE_WITHDRAW || $transactionType === Transaction::TYPE_TRANSFER) {
                $this->accountService->debit($senderPhoneNumber, (int)$amount + (int)$feeAmount);
                $this->accountService->credit($receiverPhoneNumber, (int)$amount);
            } elseif ($transactionType === Transaction::TYPE_DEPOSIT) {
                $this->accountService->credit($receiverPhoneNumber, (int)$amount);
                $this->accountService->debit($senderPhoneNumber, (int)$amount + (int)$feeAmount);
            }

            // Création de la transaction
            $transaction = Transaction::create([
                'senderId' => $senderAccount->userId,
                'receiverId' => $receiverAccount->userId,
                'sender' => $senderAccount->user, // Assurez-vous que 'user' est bien la relation vers l'utilisateur
                'receiver' => $receiverAccount->user, // Idem ici
                'amount' => $amount - $feeAmount,
                'feeAmount' => $feeAmount,
                'transactionType' => $transactionType,
                'status' => Transaction::STATUS_PENDING,
                'currency' => $currency,
            ]);


            $transaction->load(['sender', 'receiver']);
            return $transaction;
        });
    }

    function createPurchaseTransaction(
        string $senderPhoneNumber,
        float $amount,
        float $feeAmount,
        string $currency,
        array $purchaseDetails
    ): Transaction {
        $sender = User::with('account')->where('phoneNumber', $senderPhoneNumber)->first();
        if (!$sender) {
            throw new ModelNotFoundException("Compte de l'expéditeur n'existe pas.");
        }

        if (!$sender->account) {
            throw new ModelNotFoundException("Compte de l'expéditeur introuvable.");
        }

        $totalAmount = $amount + $feeAmount;
        if ($sender->account->balance < $totalAmount) {
            throw new \Exception('Insufficient funds.');
        }

        return DB::transaction(function ()
        use ($sender, $amount, $feeAmount, $currency, $purchaseDetails, $senderPhoneNumber, $totalAmount) {
            $transaction = Transaction::create([
                'amount' => $amount,
                'feeAmount' => $feeAmount,
                'currency' => $currency,
                'transactionType' => Transaction::TYPE_PURCHASE,
                'status' => Transaction::STATUS_COMPLETED,
                'senderId' => $sender->id
            ]);


            CreditPurchaseTransaction::create([
                'transactionId' => $transaction->id,
                'receiverName' => $purchaseDetails['receiverName'],
                'receiverPhoneNumber' => $purchaseDetails['receiverPhoneNumber'],
                'receiverEmail' => $purchaseDetails['receiverEmail'],
            ]);

            $this->accountService->debit($senderPhoneNumber, $totalAmount);
            $transaction->status = Transaction::STATUS_COMPLETED;
            $transaction->save();
            $transaction->load(["sender", "creditPurchase"]);
            return $transaction;
        });
    }
}
