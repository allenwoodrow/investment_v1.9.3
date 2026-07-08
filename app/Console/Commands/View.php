<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class View extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view {name : The name of the view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $name = $this->argument('name');
        $serviceClass = $name;

        // $path = 

        $path = resource_path('views') . '/' . $name . '.blade.php';

        if (File::exists($path)) {
            $this->error('view already exists!');
            return;
        }

        if(!is_dir(resource_path('views') . '/')) {
            File::makeDirectory(resource_path('views'));
        }

        // $stub = str_replace(
        //     'YourServiceName',
        //     $serviceClass,
        //     File::get(__DIR__ . '/stubs/service.stub')
        // );
        $stub = "";

        File::put($path, $stub);

        $this->info("$name View created successfully!");
    }
}
