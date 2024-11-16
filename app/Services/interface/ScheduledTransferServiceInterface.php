<?php

namespace App\Services\Interface;

interface ScheduledTransferServiceInterface
{
    /**
     * Create a new scheduled transfer
     */
    public function createScheduledTransfer(
        int $userId,
        string $receiverPhoneNumber,
        float $amount,
        string $frequency,
        float $feeAmount,
        string $currency
    );

    /**
     * Cancel a scheduled transfer
     */
    public function cancelScheduledTransfer(int $transferId, int $userId);

    /**
     * Get all scheduled transfers for a user
     */
    public function getUserScheduledTransfers(int $userId);

    /**
     * Process due scheduled transfers
     */
    public function processDueTransfers();
}
