<?php

namespace App\Services;

use App\Models\Account;
use App\Services\interface\AccountServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountService implements AccountServiceInterface
{
    public function debit(string $phoneNumber, float $amount)
    {
        $account = Account::whereHas('user', function ($query) use ($phoneNumber) {
            $query->where('phoneNumber', $phoneNumber);
        })->first();

        if (!$account) {
            throw new ModelNotFoundException("Compte non trouvé.");
        }

        if ($account->balance < $amount) {
            throw new \Exception("Fonds insuffisants.");
        }

        $account->balance -= (int)$amount;
        $account->save();

        return $account;
    }

    public function credit(string $phoneNumber, float $amount)
    {
        $account = Account::whereHas('user', function ($query) use ($phoneNumber) {
            $query->where('phoneNumber', $phoneNumber);
        })->first();

        if (!$account) {
            throw new ModelNotFoundException("Compte non trouvé.");
        }

        $newBalance = $account->balance + (int)$amount;
        if ($newBalance > (float) $account->plafond) {
            throw new \Exception("Impossible de créditer le compte : le solde limite de {$account->plafond} FCFA est dépassé.");
        }

        $account->balance += (int)$amount;
        $account->save();

        return $account;
    }

    public function validateAccounts(string $senderPhoneNumber, string $receiverPhoneNumber)
    {
        $senderAccount = Account::with('user')->whereHas('user', function ($query) use ($senderPhoneNumber) {
            $query->where('phoneNumber', $senderPhoneNumber);
        })->first();

        $receiverAccount = Account::with('user')->whereHas('user', function ($query) use ($receiverPhoneNumber) {
            $query->where('phoneNumber', $receiverPhoneNumber);
        })->first();

        if (!$senderAccount) {
            throw new \Exception("Compte de l'expéditeur n'existe pas");
        }
        if (!$receiverAccount) {
            throw new \Exception("Compte du destinataire n'existe pas");
        }

        return ['senderAccount' => $senderAccount, 'receiverAccount' => $receiverAccount];
    }

    public function getAccountByUser(string $userId)
    {
        return Account::where('userId', $userId)->firstOrFail();
    }
}
