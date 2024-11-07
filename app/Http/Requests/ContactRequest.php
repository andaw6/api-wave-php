<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
        $senegalPhoneNumberRegex = '/^(?:\+221)?(77|76|75|78|70)\d{7}$/';
        return [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'regex:' . $senegalPhoneNumberRegex, 'exists:users,phoneNumber'],
        ];
    }


    public function messages()
    {
        return [
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'name.required' => 'Le nom est requis.',
            'phone.required' => 'Le numéro de téléphone est requis.',
            'phone.regex' => "Le numéro de téléphone doit être un numéro sénégalais valide.",
            'phone.exists' => "Le numéro de téléphone doit exister dans la base de données.",
        ];
    }
}
