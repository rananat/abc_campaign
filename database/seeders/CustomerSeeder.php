<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Customer::factory()->count(200)->hasPurchaseTransactions(10)->create();
        \App\Models\Customer::factory()->count(200)->hasPurchaseTransactions(20)->create();
        \App\Models\Customer::factory()->count(200)->hasPurchaseTransactions(5)->create();
        \App\Models\Customer::factory()->count(200)->hasPurchaseTransactions(10)->create();
        \App\Models\Customer::factory()->count(100)->hasPurchaseTransactions(15)->create();
        \App\Models\Customer::factory()->count(100)->hasPurchaseTransactions(1)->create();
        \App\Models\Customer::factory()->count(200)->hasPurchaseTransactions(12)->create();
    }
}
