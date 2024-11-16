<?php

namespace App\Services\interface;

interface TransactionServiceInterface
{
    function getTransactionById(
        string $id
    );
    function getAllByUser(
        string $userId,
        $timeFrame = null,
        $page = 1,
        $limit = 10,
        $include = null,
    );
    function createTransaction(
        string $senderPhoneNumber,
        string $receiverPhoneNumber,
        float $amount,
        float $feeAmount,
        string $currency,
        string $transactionType
    );
    function createPurchaseTransaction(
        string $senderPhoneNumber,
        float $amount,
        float $feeAmount,
        string $currency,
        array $purchaseDetails
    );
    function getAll(
        $timeFrame = null,
        $page = 1,
        $limit = 10,
        $include = null,
    );

    public function createBatchTransfers(
        string $senderPhoneNumber,
        array $recipients,
        string $currency = "XOR",
        float $feeAmount = 0
    );

    public function tranferMultiple(array $data);
}
