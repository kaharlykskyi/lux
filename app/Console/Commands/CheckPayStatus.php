<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPayStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paystatus:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check pay status for wait checkout';

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
     * @return void
     */
    public function handle()
    {
        \App\Services\CheckPayStatus::getInstance();
    }
}
