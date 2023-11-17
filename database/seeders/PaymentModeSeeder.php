<?php

namespace Database\Seeders;

use App\Models\PaymentMode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_methods=[
            [
                'name'=>'stripe',
                'status'=>true
            ],
            [
                'name'=>'google_pay',
                'status'=>false
            ],
            [
                'name'=>'bank_deposit',
                'status'=>false
            ],
        ];
        foreach ($payment_methods as $pm){
            PaymentMode::create($pm);
        }
    }
}
