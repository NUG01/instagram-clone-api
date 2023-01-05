<?php

namespace App\Http\Controllers;

use App\Mail\RegisterEmail;
use App\Mail\VerificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function sendEMail($username, $email, $verificationCode, $subject, $views){
      $data=([
        'email'             => $email,
        'verification_code' => $verificationCode,
        'username'          => $username,
        'subject'           => $subject,
        'views'             => $views,
      ]);
      Mail::to($email)->send(new VerificationEmail($data));
    }
}
