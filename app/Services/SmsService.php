<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SmsService
{

    protected string $baseUrl = 'https://www.amkdelivery.com/api-rest-sms/api';
    protected string $token = 'dChYoMH6CmOugKklGEuBp4brdWyCPd^NehpJ8pSiQUDEYCUpNl1CVmMc*x&x';

    public function sendWelcomeSms($phone, $name)
    {
        Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("{$this->baseUrl}/insertTabmensajes", [
            'numero' => $phone,
            'mensaje' => "¡Bienvenido a La Casa del Chantilly! Gracias por registrarte {$name}."
        ]);

    }

    public function send(string $phone, int $code): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("{$this->baseUrl}/insertTabmensajes", [
            'numero' => $phone,
            'mensaje' => "Tu código de recuperación es: {$code}"
        ]);

        return $response->successful();
    }

    public function sendPaymentConfirmation($phone, $orderNumber, $total)
    {
        $message = "¡Pago confirmado! Tu pedido #{$orderNumber} por S/ {$total} ha sido procesado exitosamente. Gracias por tu compra en La Casa del Chantilly.";
        
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post("{$this->baseUrl}/insertTabmensajes", [
                'numero' => $phone,
                'mensaje' => $message
            ]);

            Log::info('SMS de confirmación de pago enviado', [
                'phone' => $phone,
                'order_number' => $orderNumber,
                'response_status' => $response->status()
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error enviando SMS de confirmación', [
                'phone' => $phone,
                'order_number' => $orderNumber,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

}