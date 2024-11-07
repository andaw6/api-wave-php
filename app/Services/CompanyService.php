<?php

namespace App\Services;

use App\Models\Company;
use Exception;
use Illuminate\Support\Str;
use App\Services\interface\CompanyServiceInterface;

class CompanyService extends Service implements CompanyServiceInterface
{

    public function getById(string $id)
    {
        if (!Str::isUuid($id)) {
            throw new \Exception('Format UUID invalide pour id');
        }

        return Company::find($id);
    }

    public function getAll($page = 1, $limit = 50)
    {
        $data = Company::paginate($limit, ['*'], 'page', $page);
        return [
            'data' => $data->items(),
            ...$this->getPagination($data)
        ];
    }

}
