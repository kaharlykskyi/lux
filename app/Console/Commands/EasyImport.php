<?php

namespace App\Console\Commands;

use App\TecDoc\ImportPriceList;
use Illuminate\Console\Command;

class EasyImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy-import:start {company} {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start import http from console';

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
        new ImportPriceList((object)[
            'company' => $this->argument('company'),
            'file' => $this->argument('file')
        ],true);
    }
}
