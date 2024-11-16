<?php

namespace App\Http\Controllers;

use App\Models\ScheduledTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\interface\TransactionServiceInterface;

class ScheduledTransferController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function schedule(Request $request)
    {
        $request->validate([
            'receiver_phone_number' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:daily,weekly,monthly',
            'fee_amount' => 'numeric|min:0',
            'currency' => 'string'
        ]);

        $nextExecution = $this->calculateNextExecution($request->frequency);

        $scheduledTransfer = ScheduledTransfer::create([
            'user_id' => Auth::id(),
            'receiver_phone_number' => $request->receiver_phone_number,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'next_execution' => $nextExecution,
            'fee_amount' => $request->fee_amount ?? 0,
            'currency' => $request->currency ?? 'XOR'
        ]);

        return response()->json([
            'message' => 'Transfert planifié avec succès',
            'data' => $scheduledTransfer
        ]);
    }


    public function list()
    {
        $scheduledTransfers = ScheduledTransfer::where('user_id', Auth::id())->get();

        return response()->json([
            'data' => $scheduledTransfers
        ]);
    }

    public function cancel($id)
    {
        $scheduledTransfer = ScheduledTransfer::findOrFail($id);

        if ($scheduledTransfer->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $scheduledTransfer->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Planification annulée avec succès'
        ]);
    }

    private function calculateNextExecution($frequency)
    {
        $now = Carbon::now();

        return match ($frequency) {
            'daily' => $now->addDay(),
            'weekly' => $now->addWeek(),
            'monthly' => $now->addMonth(),
            default => $now->addDay(),
        };
    }
}
