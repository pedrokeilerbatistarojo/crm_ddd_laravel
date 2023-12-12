<?php

namespace Database\Seeders;

use Domain\Clients\Models\ClientFile;
use Illuminate\Database\Seeder;

class ClientFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ClientFile::factory(100)->create();
    }
}
