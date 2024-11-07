<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\interface\CompanyServiceInterface;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected CompanyServiceInterface $companyService;

    public function __construct(CompanyServiceInterface $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 50);

        $contacts = $this->companyService->getAll($page, $limit);
        return response()->json([
            'message' => 'Liste de tous les companies',
            ...$contacts,
        ]);
    }

    public function show(string $id)
    {
        $contact = $this->companyService->getById($id);

        if (!$contact) {
            throw new Exception("Le company n'existe pas");
        }

        return $contact;
    }
}
