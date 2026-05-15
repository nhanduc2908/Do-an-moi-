<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $expiresIn;

    public function __construct($code, $expiresIn = 10)
    {
        $this->code = $code;
        $this->expiresIn = $expiresIn;
    }

    public function build()
    {
        return $this->subject('Your Two-Factor Authentication Code')
                    ->markdown('emails.two-factor-code')
                    ->with([
                        'code' => $this->code,
                        'expiresIn' => $this->expiresIn,
                        'ipAddress' => request()->ip(),
                        'userAgent' => request()->userAgent()
                    ]);
    }
}