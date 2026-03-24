<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;
    public $resetUrl;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
        $this->resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);
    }

    public function build()
    {
        return $this->subject('Reset Your Password - ZUBEEE')
                    ->html($this->getEmailHtml());
    }

    protected function getEmailHtml()
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eef2f7; border-radius: 8px; }
                .header { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #f0f4f8; }
                .logo { font-size: 24px; font-weight: bold; color: #17320b; text-transform: uppercase; letter-spacing: 2px; }
                .content { padding: 30px 20px; text-align: center; }
                .btn { display: inline-block; padding: 14px 28px; background-color: #a8894d; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; box-shadow: 0 4px 6px rgba(168, 137, 77, 0.2); }
                .footer { text-align: center; padding-top: 20px; font-size: 12px; color: #999; border-top: 1px solid #f0f4f8; }
                .expiry { font-size: 13px; color: #666; margin-top: 25px; }
                .fallback { word-break: break-all; font-size: 11px; color: #aaa; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <div class='logo'>ZUBEEE</div>
                </div>
                <div class='content'>
                    <h2>Hello!</h2>
                    <p>You are receiving this email because we received a password reset request for your account.</p>
                    <a href='{$this->resetUrl}' class='btn'>Reset Password</a>
                    <p class='expiry'>This password reset link will expire in 60 minutes.</p>
                    <p>If you did not request a password reset, no further action is required.</p>
                    <hr style='border: none; border-top: 1px solid #f0f4f8; margin: 25px 0;'>
                    <p class='fallback'>If you're having trouble clicking the 'Reset Password' button, copy and paste the URL below into your web browser:<br>{$this->resetUrl}</p>
                </div>
                <div class='footer'>
                    &copy; " . date('Y') . " ZUBEEE Travel Agency. All rights reserved.
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
