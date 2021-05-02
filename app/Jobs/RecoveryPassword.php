<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecoveryPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private $recoveryPassword;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\RecoveryPassword $recoveryPassword)
    {
        $this->recoveryPassword = $recoveryPassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Illuminate\Support\Facades\Mail::queue(new \App\Mail\RecoveryPasswordMail($this->recoveryPassword));
    }
}
