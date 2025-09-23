<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function sendPaymentConfirmation($phone, $orderNumber, $total, $deliveryDate, $pointHistory = null, $currentPoints = 0)
    {
        $message = "¡Pago confirmado! Tu pedido #{$orderNumber} por S/ {$total} ha sido procesado exitosamente.";

        // Agregar información de puntos si existe
        if ($pointHistory) {
            if ($pointHistory->point_type === 'Acumulado') {
                $message .= " ¡Ganaste {$pointHistory->points_earned} puntos!";
            } elseif ($pointHistory->point_type === 'No acumula') {
                $message .= " Esta compra no acumula puntos.";
            } else {
                $pointsUsed = abs($pointHistory->points_earned);
                $message .= " Canjeaste {$pointsUsed} puntos.";
            }
            $message .= " Tienes {$currentPoints} puntos disponibles.";
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post("{$this->baseUrl}/insertTabmensajes", [
                'numero' => $phone,
                'mensaje' => $message
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