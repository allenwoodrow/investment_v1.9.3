<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
// use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        \App\Console\Commands\Service::class,
        \App\Console\Commands\Contract::class,
        \App\Console\Commands\View::class,
        \App\Console\Commands\RebuildFrontend::class
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('optimize:clear')->everyFiveMinutes();
        $schedule->command('queue:retry all')->everyFiveMinutes();
        $schedule->command('queue:work --sansdaemon --max_exec_time=25')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
