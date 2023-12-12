<?php

namespace Database\Seeders;

use Domain\Clients\Models\Client;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Client::factory(15)->create();
    }
}
