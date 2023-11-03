<?php

namespace App\Jobs;

use App\Http\Controllers\FacebookController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateFacebookDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            \Log::info('Updating Facebook data...');
            $this->updateFacebookData();
            \Log::info('Facebook data updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error while updating Facebook data: ' . $e->getMessage());
        }
    }

    private function updateFacebookData()
    {
        $fb = new FacebookController();
        try {
            $fb->autoUpdateFacebookData();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        ;
    }
}