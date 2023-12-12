<?php

namespace app\Apps\Default\Console\Commands;

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\EmployeesTableSeeder;
use Database\Seeders\ProductsTableSeeder;
use Database\Seeders\ProductTypesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dev-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (env('APP_ENV') !== 'production') {
            DB::statement("SET foreign_key_checks = 0;");
            Artisan::call('migrate:refresh');
            DB::statement("SET foreign_key_checks = 1;");
            $this->call(UsersTableSeeder::class);
            $this->call(ProductTypesTableSeeder::class);
            $this->call(ProductsTableSeeder::class);
            $this->call(DatabaseSeeder::class);
        }

        return 0;
    }
}
