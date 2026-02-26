<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class QueueHealthCheck implements ShouldQueue
{
    use Queueable;
    public $key;

    /**
     * Create a new job instance.
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        cache()->put($this->key, 'processed', 30);
    }
}
