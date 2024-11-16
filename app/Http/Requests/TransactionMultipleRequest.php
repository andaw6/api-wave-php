<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionMultipleRequest extends FormRequest
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
            'recipients' => 'required|array|min:1',
            'recipients.*.phone' => ['required', 'string', "regex:$senegalPhoneNumberRegex", 'exists:users,phoneNumber'],
            'currency' => 'nullable|string|in:XOF,FCA,FCFA', // Optional field
            'feeAmount' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:1'
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'recipients.required' => 'The recipients list is required.',
            'recipients.array' => 'The recipients field must be an array.',
            'recipients.min' => 'You must provide at least one recipient.',
            'recipients.*.phone.required' => 'Each recipient must have a phone number.',
            'recipients.*.phone.regex' => 'Each phone number must be a 10-digit number.',
            'currency.in' => 'The currency must be either XOF or XOR.',
            'feeAmount.required' => 'The fee amount is required.',
            'feeAmount.numeric' => 'The fee amount must be a number.',
            'feeAmount.min' => 'The fee amount cannot be negative.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 1.'
        ];
    }
}
