<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SecurityAlertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSecurityAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $recipients;
    protected $alertData;

    public function __construct(array $recipients, array $alertData)
    {
        $this->recipients = $recipients;
        $this->alertData = $alertData;
    }

    public function handle()
    {
        foreach ($this->recipients as $recipient) {
            try {
                Mail::to($recipient)->send(new SecurityAlertMail($this->alertData));
                Log::info('Security alert sent', ['recipient' => $recipient, 'alert' => $this->alertData['type']]);
            } catch (\Exception $e) {
                Log::error('Failed to send security alert', ['error' => $e->getMessage()]);
            }
        }
    }
}