<?php

namespace Database\Seeders;

use Domain\Invoices\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Invoice::factory(50)->create();
    }
}
