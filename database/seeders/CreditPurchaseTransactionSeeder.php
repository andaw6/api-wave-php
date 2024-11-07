<?php

namespace Database\Seeders;

use App\Models\CreditPurchaseTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditPurchaseTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CreditPurchaseTransaction::factory()->count(3)->create();
    }
}
