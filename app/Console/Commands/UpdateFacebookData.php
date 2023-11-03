<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\FacebookController;
use Illuminate\Support\Facades\Log;
use App\Jobs\UpdateFacebookDataJob;



class UpdateFacebookData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:fb-post-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Facebook database from API';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        try {
            Log::info('Adding update job to the queue');
            dispatch(new UpdateFacebookDataJob());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        ;
    }

    public function __construct()
    {
        parent::__construct();

    }

}