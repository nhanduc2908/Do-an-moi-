<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\WeeklyDigestMail;
use Illuminate\Support\Facades\Mail;

class SendDigestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $user;
    protected $digestData;

    public function __construct($user, $digestData)
    {
        $this->user = $user;
        $this->digestData = $digestData;
    }

    public function handle()
    {
        Mail::to($this->user->email)->send(new WeeklyDigestMail($this->digestData));
    }
}