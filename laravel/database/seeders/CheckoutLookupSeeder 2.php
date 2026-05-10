<?php

namespace Database\Seeders;

use App\Models\AddressType;
use App\Models\DeliveryMethod;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class CheckoutLookupSeeder extends Seeder
{
    public function run(): void
    {
        AddressType::firstOrCreate(['name' => 'Shipping']);

        OrderStatus::firstOrCreate(['name' => 'New']);

        PaymentMethod::firstOrCreate(['name' => 'Card']);
        PaymentMethod::firstOrCreate(['name' => 'Cash']);
        PaymentMethod::firstOrCreate(['name' => 'Bank transfer']);

        DeliveryMethod::firstOrCreate(['name' => 'Pickup'], ['price' => 0]);
        DeliveryMethod::firstOrCreate(['name' => 'Courier'], ['price' => 3.90]);
        DeliveryMethod::firstOrCreate(['name' => 'Packeta'], ['price' => 2.49]);
    }
}
