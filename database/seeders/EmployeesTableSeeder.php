<?php

namespace Database\Seeders;

use Domain\Employees\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Employee::query()->insert([
            [
                'email' => 'laura@thermasdegrinon.com',
                'first_name' => 'Laura',
                'phone' => '662789780',
                'active' => true,
                'created_by' => 1,
                'last_modified_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'cobos@thermasdegrinon.com',
                'first_name' => 'Cobos',
                'phone' => '662789780',
                'active' => true,
                'created_by' => 1,
                'last_modified_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'carmen@thermasdegrinon.com',
                'first_name' => 'Carmen',
                'phone' => '662789780',
                'active' => true,
                'created_by' => 1,
                'last_modified_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'navarro@thermasdegrinon.com',
                'first_name' => 'Navarro',
                'phone' => '662789780',
                'active' => true,
                'created_by' => 1,
                'last_modified_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'cristina@thermasdegrinon.com',
                'first_name' => 'Cristina',
                'phone' => '662789780',
                'active' => true,
                'created_by' => 1,
                'last_modified_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
