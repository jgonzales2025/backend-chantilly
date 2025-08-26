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
        $url = config("niubiz.urls.security.{$this->env}");
        
        $response = $this->client->request('POST', $url, [
            'auth' => [$this->user, $this->password],
            'timeout' => 30,
        ]);
        
        Log::info('Nuevo sessionKey generado para Niubiz');
        return $response->getBody()->getContents();
    }

    /**
     * Crear sesión de pago y guardar en BD
     */
    public function createSession($amount, $customer, $purchaseNumber, $diasRegistrado)
    {
        // Crear registro de transacción
        $transaction = NiubizTransaction::create([
            //'order_id' => $orderId,
            'purchase_number' => $purchaseNumber,
            'amount' => $amount,
            'currency' => 'PEN',
            'status' => TransactionStatusEnum::PENDING
        ]);

        try {
            $url = config("niubiz.urls.session.{$this->env}") . $this->merchantId;
            $sessionKey = $this->getSessionKey();
            
            $requestData = [
                "amount" => number_format($amount, 2, '.', ''),
                "channel" => "web",
                "antifraud" => [
                    "clientIp" => request()->ip(),
                    "merchantDefineData" => [
                        "MDD4" => $customer->email ?? '',
                        "MDD32" => $customer->id ?? '',
                        "MDD75" => 'Registrado',
                        "MDD77" => $diasRegistrado
                        ]
                    ]
            ];

            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $sessionKey
                ],
                'json' => $requestData,
                'timeout' => 10
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta', $result);
            // Actualizar con respuesta exitosa
            $transaction->update([
                'session_token' => $result['sessionKey'] ?? null
            ]);

            Log::info('Sesión Niubiz creada exitosamente', [
                'transaction_id' => $transaction->id
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
            $sessionKey = $this->getSessionKey();

            $requestData = [
                "antifraud" => null,
                "captureType" => "manual",
                "channel" => "web",
                "countable" => true,
                "order" => [
                    "amount" => $amount,
                    "currency" => "PEN",
                    "purchaseNumber" => $purchaseNumber,
                    "tokenId" => $tokenId
                ],
                "card" => [
                    "token" => $tokenId
                ]
            ];
            Log::info('Iniciando pago Niubiz', [
                'purchase_number' => $purchaseNumber,
                'request' => $requestData
            ]);

            // Actualizar con token y request
            $transaction->update([
                'token_id' => $tokenId
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
            
            Log::info('Respuesta de Niubiz', [
                'purchase_number' => $purchaseNumber,
                'response' => $result
            ]);
            // Determinar estado basado en respuesta
            $actionCode = $result['dataMap']['ACTION_CODE'] ?? null;
            $isSuccess = $actionCode === '000';
            
            $status = $isSuccess ? 
                TransactionStatusEnum::SUCCESS : 
                TransactionStatusEnum::FAILED;

            Log::info('Estado de la transacción Niubiz', [
                'purchase_number' => $purchaseNumber,
                'status' => $status->value,
                'action_code' => $actionCode
            ]);

            $httpCode = $response->getStatusCode();
            Log::info('Código HTTP de Niubiz', [
                'http_code' => $httpCode
            ]);
            // Actualizar transacción
            $transaction->update([
                'status' => $status,
                'transaction_id' => $result['dataMap']['TRANSACTION_ID'] ?? null,
                'action_code' => $actionCode,
                'transaction_date' => $result['dataMap']['TRANSACTION_DATE'] ?? null,
                'niubiz_code_http' => $httpCode,
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
