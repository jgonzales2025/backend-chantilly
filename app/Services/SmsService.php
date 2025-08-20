<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    public function sendWelcomeSms($phone, $name)
    {
        $twilio = new Client(config('app.twilio_sid'), config('app.twilio_auth_token'));

        $message = "Hola $name, gracias por registrarte en nuestra pastelería La Casa del Chantilly 🎉";

        $twilio->messages->create(
            $phone,
            [
                'from' => config('app.twilio_phone'),
                'body' => $message
            ]
        );
    }

    public function sendRecoveryCode($phone, $code)
    {
        $twilio = new Client(config('app.twilio_sid'), config('app.twilio_auth_token'));

        $message = "Tu código de recuperación es: $code";

        $twilio->messages->create(
            $phone,
            [
                'from' => config('app.twilio_phone'),
                'body' => $message
            ]
        );
    }
}