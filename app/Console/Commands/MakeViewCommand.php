<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view 
                        {name : The path to the new view not including /resources/views/}
                        {--force : Overwrite existing file by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create empty view in resources views, ';

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
     * @return mixed
     */
    public function handle()
    {
        $name = str_replace('.','/',str_replace('.blade.php','',$this->argument('name')));

        $viewNames = pathinfo($name);

        $this->createDirectories($viewNames['dirname']);
        $this->exportView($viewNames['dirname'], $viewNames['filename']);

        $this->info($name.'.blade.php created successfully.');

    }

    protected function createDirectories($path)
    {
        if (! is_dir($directory = resource_path('views/'.$path))) {
            mkdir($directory, 0755, true);
        }

    }

    protected function exportView($path, $filename)
    {
        if (file_exists($view = resource_path('views/'.$path.'/'.$filename.'.blade.php')) && ! $this->option('force')) {
            if (! $this->confirm("The [{$path}/{$filename}] view already exists. Do you want to replace it?")) {
                $this->error($view.'.blade.php not created!');
                die();
            }else{
                exec("rm $view");
//                unlink($view);
            }
        }
        touch($view);

        file_put_contents($view,"@extends('layouts.app')\n\n@section('content')\n\n@endsection");
        exec("pstorm {$view}");
    }
}
