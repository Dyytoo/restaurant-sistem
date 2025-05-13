<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderData;

    /**
     * Create a new job instance.
     */
     public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         Http::async()
            ->post(config('services.menu_service.url').'/api/validate', [
                'items' => $this->orderData['items']
            ])
            ->then(function ($response) {
                // Handle response
            });
    }
}
