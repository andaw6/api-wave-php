<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Services\interface\TransactionServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    private function validateData(string $key, array $data): array
    {
        $phone = Auth::user()->phoneNumber;
        if ($phone === $data["phone"]) {
            throw new Exception("L'expéditeur et le destinataire ne peuvent pas être identiques.");
        }
        $data[$key !== "senderPhoneNumber" ? "senderPhoneNumber" : "receiverPhoneNumber"] = $data["phone"];
        unset($data["phone"]);
        $data[$key] = $phone;
        return $data;
    }

    public function getAllTransactions(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $timeFrame = $request->query("timeFrame", null);
        $include = $request->query('include') ? explode(',', $request->query('include')) : null;
        $transactions = $this->transactionService->getAll($page, $limit, $timeFrame, $include);

        return response()->json([
            'message' => 'Liste des transactions récupérée avec succès',
            ...$transactions,
        ]);
    }

    public function getTransactionsByUser(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $timeFrame = $request->query("timeFrame", null);
        $userAuth = Auth::user();
        $transactions = $this->transactionService->getAllByUser($userAuth->id, $timeFrame, $page, $limit, ["sender", "receiver"]);

        return response()->json([
            'message' => 'Liste des transactions de l\'utilisateur récupérée avec succès',
            ...$transactions,
        ]);
    }

    public function getTransaction($id): JsonResponse
    {
        $userAuth = Auth::user();
        $transaction = $this->transactionService->getTransactionById($id);
        if (!$transaction) {
            return response()->json(['error' => "La transaction n'existe pas"], 404);
        }
        if (!in_array($userAuth->role, [User::ROLE_ADMIN, User::ROLE_AGENT]) && !($transaction->receiverId === $userAuth->id || $transaction->senderId === $userAuth->id)) {
            return response()->json(['error' => "Cette action est non autorisée"], 403);
        }
        return response()->json(['message' => "La transaction avec l'id: $id", 'data' => $transaction]);
    }

    private function handleTransaction($data, $transactionType, $successMessage)
    {
        $transaction = $this->transactionService->createTransaction(
            $data['senderPhoneNumber'],
            $data['receiverPhoneNumber'],
            $data['amount'],
            isset($data['feeAmount']) ? $data['feeAmount'] : 0,
            isset($data['currency']) ? $data["curency"] : "XOR",
            $transactionType
        );
        return ['message' => $successMessage, 'data' => $transaction];
    }

    public function deposit(TransactionRequest $request)
    {
        $data = $this->validateData("senderPhoneNumber", $request->validated());
        return $this->handleTransaction($data, Transaction::TYPE_DEPOSIT, "Transaction DEPOSIT créée avec succès");
    }

    public function withdraw(TransactionRequest $request)
    {
        $data = $this->validateData("receiverPhoneNumber", $request->validated());
        return $this->handleTransaction($data, Transaction::TYPE_WITHDRAW, "Transaction WITHDRAW créée avec succès");
    }

    public function purchase(CreditRequest $request)
    {
        $data = $this->validateData('senderPhoneNumber', $request->validated());
        return $this->handlePurchaseTransaction($data);
    }

    public function transfer(TransactionRequest $request)
    {
        $data = $this->validateData("senderPhoneNumber", $request->validated());
        return $this->handleTransaction($data, Transaction::TYPE_TRANSFER, "Transaction TRANSFER créée avec succès");
    }

    private function handlePurchaseTransaction(array $data)
    {
        try {
            $data['transactionType'] = Transaction::TYPE_PURCHASE;
            $transaction = $this->transactionService->createPurchaseTransaction(
                $data['senderPhoneNumber'],
                $data['amount'],
                isset($data['feeAmount']) ? $data['feeAmount'] : 0,
                isset($data['currency']) ? $data["curency"] : "XOR",
                [
                    'receiverName' => $data['name'],
                    'receiverPhoneNumber' => $data['receiverPhoneNumber'],
                    'receiverEmail' => isset($data['email']) ? $data["email"] : "",
                ]
            );
            return [
                'message' => "Transaction PURCHASE créée avec succès",
                'data' => $transaction,
            ];
        } catch (\Exception $e) {
            return [
                'message' => "Une erreur est survenue lors de la création de la transaction.",
                'data' => $e->getMessage(),
            ];
        }
    }
}
