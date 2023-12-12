<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        if (env('APP_ENV') === 'local') {
            $this->call(UsersTableSeeder::class);
            $this->call(CategoriesTableSeeder::class);
            $this->call(ProductTypesTableSeeder::class);
            $this->call(ProductsTableSeeder::class);
            $this->call(EmployeesTableSeeder::class);
            $this->call(ClientsTableSeeder::class);
            $this->call(ClientNotesTableSeeder::class);
            $this->call(ClientFilesTableSeeder::class);
            $this->call(OrdersTableSeeder::class);
            $this->call(OrderDetailsTableSeeder::class);
            $this->call(CircuitReservationsTableSeeder::class);
            $this->call(TreatmentReservationsTableSeeder::class);
            $this->call(InvoicesTableSeeder::class);
            $this->call(GymFeeTypesTableSeeder::class);
        }
    }
}
