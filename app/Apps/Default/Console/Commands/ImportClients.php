<?php

namespace app\Apps\Default\Console\Commands;

use app\Imports\ClientImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-clients {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import clients from xls';

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

        Excel::import(app(ClientImport::class), $this->argument('file'));

        $this->info('Importación finalizada.');

        return 0;
    }
}
