<?php

namespace App\Interface;

interface CreditPurchaseDetails
{
    public function getReceiverName(): string;
    public function getReceiverPhoneNumber(): string;
    public function getReceiverEmail(): ?string; 
}
