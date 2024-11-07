<?php

namespace App\Services;

use App\Models\Contact;
use App\Services\interface\ContactServiceInterface;
use Exception;
use Illuminate\Support\Str;

class ContactService extends Service implements ContactServiceInterface
{
    public function toggleFavorite(string $id)
    {
        $contact = $this->getById($id);
        if (!$contact) {
            throw new \Exception("Le contact n'existe pas");
        }
        $contact->favorite = !$contact->favorite;
        $contact->save();

        return $contact;
    }

    public function getById(string $id)
    {
        if (!Str::isUuid($id)) {
            throw new \Exception('Format UUID invalide pour id');
        }

        return Contact::with("user")->find($id);
    }

    public function createContact(array $data)
    {
        $existingContact = Contact::where('userId', $data['userId'])
            ->where('phoneNumber', $data['phoneNumber'])
            ->first();

        if ($existingContact) {
            throw new Exception("Le contact existe déjà");
        }
        return Contact::create($data);
    }

    public function getAll($page = 1, $limit = 10)
    {
        $data = Contact::paginate($limit, ['*'], 'page', $page);
        return [
            'data' => $data->items(),
            ...$this->getPagination($data)
        ];
    }

    public function getByPhone(string $phone)
    {
        return Contact::where('phoneNumber', $phone)->with("user")->first();
    }

    public function getByUser(string $userId, $page = 1, $limit = 10)
    {
        if (!Str::isUuid($userId)) {
            throw new \Exception('Format UUID invalide pour userId');
        }

        $data = Contact::where('userId', $userId)->paginate($limit, ['*'], 'page', $page);
        return [
            'data' => $data->items(),
            ...$this->getPagination($data)
        ];
    }
}
