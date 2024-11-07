<?php

namespace App\Services\interface;

interface AccountServiceInterface
{
    public function debit(string $phoneNumber, float $amount);
    
    public function credit(string $phoneNumber, float $amount);
    
    public function validateAccounts(string $senderPhoneNumber, string $receiverPhoneNumber);
    
    public function getAccountByUser(string $userId);
}
