<?php

namespace Database\Seeders;

use Domain\Orders\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        OrderDetail::factory(150)->create();
    }
}
