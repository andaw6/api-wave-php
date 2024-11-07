<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
            'amount' => 'required|numeric|min:0',
            'companyId' => 'required|uuid',
        ];
    }


    public function messages()
    {
        return [
            'amount.required' => "Le montant est requis.",
            'amount.numeric' => "Le montant doit être un nombre.",
            'amount.min' => "Le montant doit être supérieur ou égal à 0.",
            'companyId.required' => "L'ID de l'entreprise est requis.",
            'companyId.uuid' => "L'ID de l'entreprise doit être un UUID valide.",
        ];
    }
}
