<?php

namespace App\Http\Requests;

use App\Rules\TelephoneRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{  /**
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
           'phone' => ['required','string', new TelephoneRule()], // Correction: unique pour l'email
           'password' => 'required|string',
       ];
   }

   public function messages(): array
   {
       return [
           'phone.required' => 'Le téléphone est obligatoire.',
           'password.required' => 'Le mot de passe est obligatoire.',
       ];
   }
}
