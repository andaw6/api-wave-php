<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sender = User::whereIn('role', [User::ROLE_VENDOR, User::ROLE_CLIENT])->inRandomOrder()->first();
        $receiver = User::where('id', '!=', $sender->id)
                        ->whereIn('role', [User::ROLE_VENDOR, User::ROLE_CLIENT])
                        ->inRandomOrder()
                        ->first();
        
        return [
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'senderId' => $sender->id,
            'receiverId' => $receiver->id,
            'feeAmount' => $this->faker->randomFloat(2, 0.5, 10),
            'currency' => 'FCFA',
            'transactionType' => $this->faker->randomElement([
                Transaction::TYPE_DEPOSIT,
                Transaction::TYPE_WITHDRAW,
                // Transaction::TYPE_PURCHASE,
                Transaction::TYPE_TRANSFER
            ]),
            'status' => $this->faker->randomElement([
                Transaction::STATUS_PENDING,
                Transaction::STATUS_COMPLETED,
                Transaction::STATUS_FAILED,
                Transaction::STATUS_CANCELLED
            ]),
        ];
        
    }
}
