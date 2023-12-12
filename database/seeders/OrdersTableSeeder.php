<?php

namespace Database\Seeders;

use Domain\Orders\Models\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Order::factory(50)->create();
    }
}
