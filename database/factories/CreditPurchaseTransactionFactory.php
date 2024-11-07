<?php

namespace Database\Factories;

use App\Facades\SenegalPhoneNumberFacade;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditPurchaseTransaction>
 */
class CreditPurchaseTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transactionId' => Transaction::factory()->create([
                'transactionType' => Transaction::TYPE_PURCHASE, 
            ])->id,
            'receiverName' => $this->faker->name,
            'receiverPhoneNumber' => SenegalPhoneNumberFacade::generate(),
            'receiverEmail' => $this->faker->safeEmail,
        ];
    }
}
