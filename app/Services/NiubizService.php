<?php

namespace App\Services;

use App\Enum\TransactionStatusEnum;
use App\Models\NiubizTransaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NiubizService
{
    protected $client;
    protected $merchantId;
    protected $user;
    protected $password;
    protected $env;

    public function __construct()
    {
        $this->merchantId = config('niubiz.merchant_id');
        $this->user = config('niubiz.user');
        $this->password = config('niubiz.password');
        $this->env = config('niubiz.pro') ? 'prod' : 'sandbox';
        $this->client = new Client(['verify' => false]);
    }

    /**
     * Obtener sessionKey con caché
     */
    public function getSessionKey()
    {
        $cacheKey = "niubiz_session_key_{$this->env}";
        
        return Cache::remember($cacheKey, 3600, function () {
            $url = config("niubiz.urls.security.{$this->env}");
            
            $response = $this->client->request('POST', $url, [
                'auth' => [$this->user, $this->password],
                'timeout' => 30,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        });
    }

    /**
     * Crear sesión de pago y guardar en BD
     */
    public function createSession($amount, $purchaseNumber, $orderId = null)
    {
        // Crear registro de transacción
        $transaction = NiubizTransaction::create([
            'order_id' => $orderId,
            'purchase_number' => $purchaseNumber,
            'amount' => $amount,
            'currency' => 'PEN',
            'status' => TransactionStatusEnum::PENDING
        ]);

        try {
            $url = config("niubiz.urls.session.{$this->env}") . $this->merchantId;
            $sessionKey = $this->getSessionKey()['sessionKey'] ?? null;

            $requestData = [
                "amount" => $amount * 100, // Convertir a centavos
                "channel" => "web",
                "antifraud" => null,
                "purchaseNumber" => $purchaseNumber
            ];

            // Guardar request en BD
            $transaction->update(['niubiz_request' => $requestData]);

            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $sessionKey
                ],
                'json' => $requestData,
                'timeout' => 30
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // Actualizar con respuesta exitosa
            $transaction->update([
                'session_token' => $result['sessionToken'] ?? null,
                'niubiz_response' => $result
            ]);

            Log::info('Sesión Niubiz creada exitosamente', [
                'transaction_id' => $transaction->id,
                'purchase_number' => $purchaseNumber
            ]);

            return $result;

        } catch (RequestException $e) {
            // Guardar error en BD
            $transaction->update([
                'status' => TransactionStatusEnum::FAILED,
                'error_message' => $e->getMessage(),
                'niubiz_response' => ['error' => $e->getMessage()]
            ]);

            Log::error('Error creando sesión Niubiz', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);

            throw new \Exception('Error al crear sesión de pago: ' . $e->getMessage());
        }
    }

    /**
     * Procesar pago y actualizar BD
     */
    public function processPayment($tokenId, $amount, $purchaseNumber)
    {
        // Buscar transacción existente
        $transaction = NiubizTransaction::where('purchase_number', $purchaseNumber)->first();
        
        if (!$transaction) {
            throw new \Exception('Transacción no encontrada');
        }

        try {
            $url = config("niubiz.urls.transaction.{$this->env}") . $this->merchantId;
            $sessionKey = $this->getSessionKey()['sessionKey'] ?? null;

            $requestData = [
                "antifraud" => null,
                "captureType" => "manual",
                "channel" => "web",
                "countable" => true,
                "order" => [
                    "amount" => $amount * 100, // Convertir a centavos
                    "currency" => "PEN",
                    "purchaseNumber" => $purchaseNumber,
                    "tokenId" => $tokenId
                ]
            ];

            // Actualizar con token y request
            $transaction->update([
                'token_id' => $tokenId,
                'niubiz_request' => array_merge($transaction->niubiz_request ?? [], $requestData)
            ]);

            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $sessionKey
                ],
                'json' => $requestData,
                'timeout' => 30
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            // Determinar estado basado en respuesta
            $actionCode = $result['dataMap']['ACTION_CODE'] ?? null;
            $isSuccess = $actionCode === '000';
            
            $status = $isSuccess ? 
                TransactionStatusEnum::SUCCESS : 
                TransactionStatusEnum::FAILED;

            // Actualizar transacción
            $transaction->update([
                'status' => $status,
                'transaction_id' => $result['dataMap']['TRANSACTION_ID'] ?? null,
                'action_code' => $actionCode,
                'transaction_date' => $result['dataMap']['TRANSACTION_DATE'] ?? null,
                'niubiz_response' => $result,
                'error_message' => $isSuccess ? null : ($result['dataMap']['ACTION_DESCRIPTION'] ?? 'Error desconocido')
            ]);

            // Si es exitoso y hay orden, marcarla como pagada
            if ($isSuccess && $transaction->order) {
                $transaction->order->markAsPaid('niubiz');
            }

            Log::info('Pago Niubiz procesado', [
                'transaction_id' => $transaction->id,
                'purchase_number' => $purchaseNumber,
                'status' => $status->value,
                'action_code' => $actionCode
            ]);

            return $result;

        } catch (RequestException $e) {
            // Guardar error y incrementar reintentos
            $transaction->update([
                'status' => TransactionStatusEnum::FAILED,
                'error_message' => $e->getMessage(),
                'niubiz_response' => array_merge($transaction->niubiz_response ?? [], ['error' => $e->getMessage()])
            ]);
            
            $transaction->incrementRetry();

            Log::error('Error procesando pago Niubiz', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
                'retry_count' => $transaction->retry_count
            ]);

            throw new \Exception('Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Obtener transacción por purchase number
     */
    public function getTransaction($purchaseNumber)
    {
        return NiubizTransaction::where('purchase_number', $purchaseNumber)->first();
    }
}
