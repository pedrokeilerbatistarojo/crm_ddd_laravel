<?php

namespace Database\Seeders;

use Domain\Gyms\Models\GymFeeType;
use Illuminate\Database\Seeder;

class GymFeeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        GymFeeType::factory(5)->create();
    }
}
