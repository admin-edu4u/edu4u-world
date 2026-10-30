<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TaskList;

class NewTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $isContinue;
    public $isHold;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $isContinue, $isHold)
    {
        $this->user_id = $user_id;
        $this->isContinue = $isContinue;
        $this->isHold = $isHold;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return TaskList::createTask($this->user_id, $this->isContinue, $this->isHold);
    }
}
