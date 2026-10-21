<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Order\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PamentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'description' => 'Pay in cash when your order is delivered to your doorstep.',
                'is_active' => true,
            ],
            [
                'name' => 'Pay at pickup',
                'code' => 'pay_at_pickup',
                'description' => 'Pick up your order at our store and pay there.',
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate([
                'code' => $method['code'],
            ], $method);
        }
    }
}
