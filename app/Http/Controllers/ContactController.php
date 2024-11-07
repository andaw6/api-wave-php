<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactPhoneRequest;
use App\Http\Requests\ContactRequest;
use App\Services\interface\ContactServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{

    protected ContactServiceInterface $contactService;

    public function __construct(ContactServiceInterface $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $contacts = $this->contactService->getAll($page, $limit);
        return response()->json([
            'message' => 'Liste de tous les contacts',
            ...$contacts,
        ]);
    }


    public function current(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $contacts = $this->contactService->getByUser(Auth::user()->id, $page, $limit);
        return response()->json([
            'message' => 'Liste de tous les contacts de l\'utilisateur connecter',
            ...$contacts,
        ]);
    }


    public function show(string $id) {
        $contact = $this->contactService->getById($id);

        if (!$contact) {
            throw new Exception("Le contact n'existe pas");
        }

        return $contact;
    }


    public function phone(ContactPhoneRequest $request) {

        $data = $request->validated(); 
        $contact = $this->contactService->getByPhone($data["phone"]);

        if (!$contact) {
            throw new Exception("Le contact n'existe pas");
        }

        return $contact;
    }


    public function store(ContactRequest $request) {
        $request->validated(); 
        $data = $request->only(['name', 'phone']);
        $data["userId"] = Auth::user()->id;
        $data['phoneNumber']=$data['phone'];
        unset($data['phone']);
        return $this->contactService->createContact(($data));
    }


    public function toggleFavorite(string $id) {
        $this->show($id);
        return $this->contactService->toggleFavorite($id);
    }
}
