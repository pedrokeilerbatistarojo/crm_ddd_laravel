<?php

namespace app\Apps\Default\Console\Commands;

use Illuminate\Console\Command;
use app\Imports\ClientsLocalityImport;
use Maatwebsite\Excel\Facades\Excel;

class FillClientsLocality extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-clients-locality {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import clients locality from xls';

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
        ini_set('memory_limit', '3000M');

        Excel::import(app(ClientsLocalityImport::class), $this->argument('file'));

        $this->info('Importación finalizada.');

        return 0;
    }
}
