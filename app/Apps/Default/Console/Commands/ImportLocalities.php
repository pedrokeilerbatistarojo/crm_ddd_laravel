<?php

namespace app\Apps\Default\Console\Commands;

use App\Jobs\ZipCodes\UpsertZipCode\UpsertZipCode;
use App\Support\Bus\Decorators\Validator\ValidatorBus;
use App\Support\Constants;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\EmployeesTableSeeder;
use Database\Seeders\ProductsTableSeeder;
use Database\Seeders\ProductTypesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Domain\Localities\Models\Locality;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ImportLocalities extends Command
{
    // https://github.com/inigoflores/ds-codigos-postales-ine-es

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-localities {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import localities from csv';

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
        $this->info('Importado Localidades');

        if ($handler = fopen($this->argument('filepath'), 'rb')) {
            $lines = count(file($this->argument('filepath')));
            $bar = $this->output->createProgressBar($lines - 1);
            $lineNumber = 1;
            while (!feof($handler)) {
                $line = fgetcsv($handler, 1024);
                $provinceCode = substr($line[0], 0, 2);
                if ($lineNumber > 1 && (int) $provinceCode > 0)
                {
                    $data = [
                        'zip_code' => $line[0],
                        'municipio_id' => $line[1],
                        'locality' => $line[2],
                        'population_unit_code' => $line[3],
                        'singular_entity_name' => $line[4],
                        'population' => $line[5],
                        'province_id' => (int) $provinceCode
                    ];
                    Locality::query()->upsert($data, array_keys($data));
                }
                $bar->advance();
                $lineNumber++;
            }
            $bar->finish();
            fclose($handler);
        }

        $this->info('Importación finalizada.');

        return 0;
    }
}
