<?php

namespace Database\Factories;

use App\Facades\QrcodeFacade;
use App\Facades\SenegalPhoneNumberFacade;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        $telephone = SenegalPhoneNumberFacade::generate();

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('0000'),
            'phoneNumber' => $telephone,
            'isActive' => $this->faker->boolean,
            'role' => $this->faker->randomElement([User::ROLE_VENDOR, User::ROLE_CLIENT]),
        ];
    }

}
