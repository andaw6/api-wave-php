<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $senegalPhoneNumberRegex = '/^(?:\+221)?(77|76|75|78|70)\d{7}$/';

        $rules = [
            'amount' => ['required', 'numeric', 'min:5'],
            'phone' => ['required', 'regex:' . $senegalPhoneNumberRegex, 'exists:users,phoneNumber'],
            'feeAmount' => ['nullable', 'numeric', 'max:99999'],
            'currency' => ['nullable', 'string'],
        ];
        return $rules;
    }       


    public function messages()
    {
        return [
            'amount.min' => 'Le montant doit être supérieur ou égal à 5.',
            'amount.numeric' => 'Le montant doit être un nombre valide.',
            'phone.regex' => "Le numéro de téléphone du destinataire doit être un numéro sénégalais valide.",
            'feeAmount.max' => 'Les frais dépassent la limite autorisée.',
        ];
    }
}
