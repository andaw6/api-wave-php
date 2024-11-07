<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'userId' => User::inRandomOrder()->first()->id,
            'companyId' => Company::inRandomOrder()->first()->id,
            'amount' => $this->faker->numberBetween(1000, 15000),
            'currency' => 'FCFA',
            'status' => $this->faker->randomElement([Bill::STATUS_PENDING, Bill::STATUS_PAID, Bill::STATUS_OVERDUE]),
        ];
    }
}
