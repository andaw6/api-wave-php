<?php
namespace App\Services;

use App\Services\Interface\ScheduledTransferServiceInterface;
use App\Services\Interface\TransactionServiceInterface;
use App\Models\ScheduledTransfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ScheduledTransferService implements ScheduledTransferServiceInterface
{
    protected $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function createScheduledTransfer(
        int $userId,
        string $receiverPhoneNumber,
        float $amount,
        string $frequency,
        float $feeAmount,
        string $currency
    ) {
        // Validate frequency
        if (!in_array($frequency, ['daily', 'weekly', 'monthly'])) {
            throw new InvalidArgumentException('Invalid frequency');
        }

        // Get sender's phone number
        $sender = User::findOrFail($userId);

        // Calculate next execution
        $nextExecution = $this->calculateNextExecution($frequency);

        // Create scheduled transfer
        return ScheduledTransfer::create([
            'user_id' => $userId,
            'receiver_phone_number' => $receiverPhoneNumber,
            'amount' => $amount,
            'frequency' => $frequency,
            'next_execution' => $nextExecution,
            'fee_amount' => $feeAmount,
            'currency' => $currency ?: 'XOR',
            'status' => 'active'
        ]);
    }

    public function cancelScheduledTransfer(int $transferId, int $userId)
    {
        $transfer = ScheduledTransfer::findOrFail($transferId);

        if ($transfer->user_id !== $userId) {
            throw new InvalidArgumentException('Unauthorized access');
        }

        $transfer->update(['status' => 'cancelled']);

        return $transfer;
    }

    public function getUserScheduledTransfers(int $userId)
    {
        return ScheduledTransfer::where('user_id', $userId)
            ->orderBy('next_execution')
            ->get();
    }

    public function processDueTransfers()
    {
        $now = Carbon::now();

        $transfers = ScheduledTransfer::where('status', 'active')
            ->where('next_execution', '<=', $now)
            ->with('user')
            ->get();

        foreach ($transfers as $transfer) {
            try {
                // Execute transfer
                $this->executeTransfer($transfer);

                // Update next execution date
                $nextExecution = $this->calculateNextExecution(
                    $transfer->frequency,
                    $transfer->next_execution
                );

                $transfer->update(['next_execution' => $nextExecution]);

            } catch (\Exception $e) {
                Log::error("Failed to process scheduled transfer #{$transfer->id}: " . $e->getMessage());
                continue;
            }
        }

        return $transfers;
    }

    private function executeTransfer(ScheduledTransfer $transfer)
    {
        return $this->transactionService->createTransaction(
            $transfer->user->phone_number,
            $transfer->receiver_phone_number,
            $transfer->amount,
            $transfer->fee_amount,
            $transfer->currency,
            'SCHEDULED_TRANSFER'
        );
    }

    private function calculateNextExecution(string $frequency, ?Carbon $from = null)
    {
        $date = $from ? Carbon::parse($from) : Carbon::now();

        return match($frequency) {
            'daily' => $date->addDay(),
            'weekly' => $date->addWeek(),
            'monthly' => $date->addMonth(),
            default => $date->addDay(),
        };
    }
}
