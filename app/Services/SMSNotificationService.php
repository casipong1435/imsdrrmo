<?php

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;

class SMSNotificationService
{
    /**
     * Send a notification to one or multiple users.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|array  $users
     * @param  array  $details
     * @return void
     */
    protected $twilio;
    protected $phone;

    public function __construct()
    {
        $sid = getenv('TWILIO_SID');
        $token = getenv('TWILIO_TOKEN');
        $this->phone = getenv('TWILIO_PHONE');
        $this->twilio = new Client($sid, $token);
    }

    public function sendSMSAlert($numbers, $message)
    {

        foreach($numbers as $number){
            $this->twilio->messages->create(
                '+63' . $number,  // To
                [
                    'body' =>
                        $message,
                    'from' => $this->phone,
                ]
            );
        }
    }

    public function sendOTP($recepient)
    {
        // Cache::forget("otp_{$recepient}");
        $otp = random_int(100000, 999999);  // Generate a 6-digit OTP
        Cache::put("otp_{$recepient}", $otp, now()->addMinutes(5));
        $message = $this->twilio->messages->create(
            '+63' . $recepient,  // To
            [
                'body' =>
                    "Your OTP is: $otp. It will expire in 5 minutes.",
                'from' => $this->phone,
            ]
        );
    }

    public function verifyOTP($otp, $recepient)
    {
        // Retrieve OTP from cache
        $cachedOtp = Cache::get("otp_{$recepient}");
        // dd($cachedOtp);
        if ($cachedOtp == $otp) {
            // OTP is valid
            Cache::forget("otp_{$recepient}");  // Remove OTP after successful verification
            return true;
        } else {
            return false;
        }
    }
}
