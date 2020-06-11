<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class SoppyMakeRoutes extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soppy:make-routes
                            {--prefix=app : Looks for routes that start with prefix followed by a period. E.g. `->name("app.home")` }
                            {--dest : [default: "resources/vue/app/src/router/routes.json"] }';

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
        $prefix = $this->option('prefix');
        $dest = $this->option('dest') ?: str_replace('app', $prefix, 'resources/vue/app/src/router/routes.json');

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
        $count = $json->count();
        $file = fopen(base_path($dest), 'w');
        fwrite($file, $json->toJson());
        fclose($file);
        echo "Routes made ($count) => " . base_path($dest) . "\n";
    }
}
