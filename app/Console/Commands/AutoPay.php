<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;

use Carbon\Carbon;

class AutoPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'background:profits {name : The name of the background task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        // $subs = S
    }
}
