<?php

namespace App\Console\Commands;

use App\TecDoc\ImportPriceList;
use Illuminate\Console\Command;

class MakeImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start import price-list';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        new ImportPriceList();
    }
}
