<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\BillRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\interface\BillServiceInterface;

class BillController extends Controller
{

    protected BillServiceInterface $billService;

    public function __construct(BillServiceInterface $billService)
    {
        $this->billService = $billService;
    }

    public function index(Request $request)
    {

        $page = $request->query('page', 1);
        $limit = $request->query('limit', 50);
        $timeFrame = $request->query("timeFrame", null);
        $bills = $this->billService->getAll($timeFrame, $page, $limit);
        return response()->json([
            'message' => 'Liste de tous les factures ou bills',
            ...$bills,
        ]);
    }

    public function current(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $timeFrame = $request->query("timeFrame", null);
        $userAuth = Auth::user();
        $bills = $this->billService->getAllByUser($userAuth->id, $timeFrame, $page, $limit);
        return response()->json([
            'message' => 'Liste de tous vos factures ou bills',
            ...$bills,
        ]);
    }

    public function show(string $id)
    {
        $bill = $this->billService->getById($id);

        if (!$bill) {
            throw new Exception("La facture ou le bill n'existe pas");
        }

        return $bill;
    }

    public function store(BillRequest $request)
    {
        $request->validated();
        $data = $request->only(['amount', 'companyId']);
        $data["userId"] = Auth::user()->id;
        return  [
            "data" => $this->billService->createBill(
                $data['userId'],
                $data['amount'],
                $data['companyId']
            )
        ];
    }
}
