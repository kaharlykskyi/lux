<?php

namespace App\Console\Commands;

use App\Services\AutoDiscount;
use Illuminate\Console\Command;

class AutomaticDiscount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discount:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check completed order and assign auto discount';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        new AutoDiscount();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}