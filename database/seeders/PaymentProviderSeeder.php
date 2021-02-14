<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentProvider;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentProvider::create([
            'name' => 'Loop',
            'url' => 'https://superpay.view.agentur-loop.com/pay'
        ]);
    }
}
