<?php

namespace Database\Seeders;

use Domain\Clients\Models\ClientNote;
use Illuminate\Database\Seeder;

class ClientNotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ClientNote::factory(100)->create();
    }
}
