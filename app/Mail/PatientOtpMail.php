<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $type;

    public function __construct($otp, $type)
    {
        $this->otp  = $otp;
        $this->type = $type; // password_reset
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(
                $this->type === 'password_reset'
                    ? 'Password Reset OTP'
                    : 'Email Verification OTP'
            )
            ->view('emails.patient-otp')
            ->with([
                'otp'  => $this->otp,
                'type' => $this->type,
            ]);
    }
}
