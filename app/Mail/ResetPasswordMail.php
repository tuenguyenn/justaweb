<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMail extends Mailable
{
    public $resetUrl;

    public function __construct($resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        return $this->subject('Đặt lại mật khẩu')
                    ->markdown('emails.reset-password') 
                    ->with(['resetUrl' => $this->resetUrl]);
    }
}
