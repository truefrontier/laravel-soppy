<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class SoggyMakeRoutes extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soggy:make-routes {prefix=app} {dest=resources/vue/app/src/router/routes.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan your routes and create store a json file for your frontend to use';

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
     * @return mixed
     */
    public function handle() {
        $prefix = $this->argument('prefix');
        $dest = $this->argument('dest');

        $routes = Route::getRoutes();
        $json = collect();
        foreach ($routes as $route) {
            $name = $route->getName();
            $nameParts = explode('.', $name);
            if ($nameParts[0] === $prefix) {
                $path = '/' . preg_replace('/^([\/])?/', '', $route->uri);
                $path = str_replace('{', ':', $path);
                $path = str_replace('}', '', $path);

                $json->push([
                    'path' => $path,
                    'name' => $name,
                ]);
            }
        }
        $file = fopen(base_path($dest), 'w');
        fwrite($file, $json->toJson());
        fclose($file);
        echo "Routes made ===> " . base_path($dest) . "\n";
    }
}
