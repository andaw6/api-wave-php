<?php

namespace Database\Seeders;

use App\Facades\QrcodeFacade;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {

        // Vide la table users
        DB::table('users')->truncate();

        User::factory()->create([
            'name' => 'Diary Diop',
            'email' => 'diarydiop@gmail.com',
            'role' => User::ROLE_ADMIN,
            'isActive' => true,
            'password'=>Hash::make("0000"),
            'phoneNumber'=>'785910767'
        ]);

        User::factory()->create([
            'name' => 'Andaw Ciss',
            'email' => 'cissandaw@gmail.com',
            'role' => User::ROLE_AGENT,
            'isActive' => true,
            'password'=>Hash::make("0000"),
            'phoneNumber'=>'778133537'
        ]);

        for ($i = 0; $i < 10; $i++) {
            $user = User::factory()->create();
            Account::create([
                'userId' => $user->id, // Ensure this is a UUID
                'balance' => 1000,
                'currency' => 'XOF',
                'qrCode' => QrcodeFacade::generate($user->id), // your QR code logic
                'isActive' => true,
                'plafond' => 500000,
                'id' => Str::uuid(), // This should also be a UUID
            ]);
        }
    }
}
