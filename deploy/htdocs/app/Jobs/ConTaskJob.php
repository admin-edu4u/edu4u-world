<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TaskList;

class ConTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $product_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $product_id)
    {
        $this->user_id = $user_id;
        $this->product_id = $product_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return TaskList::createConTaskByProductId($this->user_id, $this->product_id);
    }
}
