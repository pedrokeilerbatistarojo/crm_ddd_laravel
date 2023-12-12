<?php

namespace Database\Seeders;

use Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::query()->create([
            'username' => 'Cyberline',
            'name' => 'Cyberline',
            'email' => 'info@cyberline.es',
            'password' => 'Cyberline',
            'default_company_id' => 1
        ]);

        User::query()->create([
            'username' => 'api-user',
            'name' => 'API User',
            'email' => 'api-user@cyberline.es',
            'password' => 'Cyberline',
            'default_company_id' => 1
        ]);
    }
}
