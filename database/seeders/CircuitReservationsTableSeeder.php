<?php

namespace Database\Seeders;

use Domain\CircuitReservations\Models\CircuitReservation;
use Illuminate\Database\Seeder;

class CircuitReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        CircuitReservation::factory(50)->create();
    }
}
