<?php

namespace Database\Seeders;

use Domain\TreatmentReservations\Models\TreatmentReservation;
use Illuminate\Database\Seeder;

class TreatmentReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        TreatmentReservation::factory(50)->create();
    }
}
