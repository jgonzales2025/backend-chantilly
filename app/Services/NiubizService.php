<?php

namespace App\Services;

use App\Enum\TransactionStatusEnum;
use App\Models\NiubizTransaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
     * Obtener token de seguridad de Niubiz
     */
    public function getSessionKey()
    {
        $url = config("niubiz.urls.security.{$this->env}");
        
        $response = $this->client->request('POST', $url, [
            'auth' => [$this->user, $this->password],
            'timeout' => 30,
        ]);
        
        return $response->getBody()->getContents();
    }

    /**
     * Crear sesión de pago y guardar en BD
     */
    public function createSession($amount, $customer, $diasRegistrado, $transaction = null)
    {
        try {
            $url = config("niubiz.urls.session.{$this->env}") . $this->merchantId;
            $sessionKey = $this->getSessionKey(); // Obteniendo token de seguridad de Niubiz
            
            // Datos a enviar en la petición a Niubiz
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

            // Envío de la petición a Niubiz para obtener el token de sesión
            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $sessionKey
                ],
                'json' => $requestData,
                'timeout' => 10
            ]);
            
            $result = json_decode($response->getBody()->getContents(), true);

            // Actualizar el token de sesión en la transacción
            $transaction->update([
                'session_token' => $result['sessionKey'] ?? null
            ]);

            return $result; // Se retorna el token de sesión

        } catch (RequestException $e) {
            // Guardar error en BD
            $transaction->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'niubiz_response' => ['error' => $e->getMessage()]
            ]);

            $transaction->incrementRetry();

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
                    "tokenId" => $tokenId // Token enviado por niubiz
                ],
                "card" => [
                    "token" => $tokenId
                ]
            ];

            // Actualizar con token y request
            $transaction->update([
                'token_id' => $tokenId
            ]);

            // Se envía la petición a niubiz para procesar el pago
            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $sessionKey
                ],
                'json' => $requestData,
                'timeout' => 30,
                'http_errors' => false
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            $httpCode = $response->getStatusCode();

            // ✅ Manejar tanto respuestas exitosas como de error
            if ($httpCode === 400 && isset($result['errorMessage'])) {
                // Es un error de negocio (fondos insuficientes, etc.)
                $actionCode = $result['data']['ACTION_CODE'] ?? '999'; // Código de error genérico
                $isSuccess = false;
                
                // Simular estructura de dataMap para consistencia
                $result['dataMap'] = [
                    'ERROR_MESSAGE' => $result['errorMessage'],
                    'ACTION_CODE' => $actionCode,
                    'ACTION_DESCRIPTION' => $result['data']['ACTION_DESCRIPTION'] ?? null,
                    'TRANSACTION_ID' => $result['data']['TRANSACTION_ID'] ?? null,
                    'TRANSACTION_DATE' => $result['data']['TRANSACTION_DATE'] ?? null,
                    'BRAND' => $result['data']['BRAND'] ?? null,
                    'CARD' => $result['data']['CARD'] ?? null,
                ];

                $transaction->incrementRetry();
                $transaction->order->markPaymentAsFailed();
            } else {
                $transaction->incrementRetry();
                // Respuesta normal de Niubiz
                $actionCode = $result['dataMap']['ACTION_CODE'] ?? null;
                $isSuccess = $actionCode === '000';
            }

            $status = $isSuccess ? 
                'success' : 
                'failed';

             // Se obtiene el código HTTP de la respuesta

            // Actualizar transacción
            $transaction->update([
                'status' => $status,
                'transaction_id' => $result['dataMap']['TRANSACTION_ID'] ?? null,
                'action_code' => $actionCode,
                'transaction_date' => $result['dataMap']['TRANSACTION_DATE'] ?? null,
                'niubiz_code_http' => $httpCode,
                'niubiz_response' => $result,
                'error_message' => $isSuccess ? null : ($result['dataMap']['ERROR_MESSAGE'] ?? 'Error desconocido')
            ]);

            // Si es exitoso y hay orden, marcarla como pagada
            if ($isSuccess && $transaction->order) {
                $transaction->order->markAsPaid('niubiz');
            }

            return $result;

        } catch (RequestException $e) {
            // Guardar error y incrementar reintentos
            $transaction->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'niubiz_response' => array_merge($transaction->niubiz_response ?? [], ['error' => $e->getMessage()])
            ]);
            
            $transaction->incrementRetry();
            $transaction->order->markPaymentAsFailed();

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
