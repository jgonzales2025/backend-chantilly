<?php

namespace App\Models;

use App\Enum\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NiubizTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'purchase_number',
        'session_token',
        'token_id',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'action_code',
        'transaction_date',
        'niubiz_code_http',
        'niubiz_response',
        'error_message',
        'retry_count',
        'last_retry_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'niubiz_request' => 'array',
        'niubiz_response' => 'array',
        'last_retry_at' => 'datetime'
    ];

    // Relación con Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function incrementRetry()
    {
        $this->increment('retry_count');
        $this->update(['last_retry_at' => now()]);
    }

    // Obtener respuesta de Niubiz formateada
    public function getNiubizResponseFormatted()
    {
        if (!$this->niubiz_response) return null;

        return [
            'action_description' => $this->niubiz_response['dataMap']['ACTION_DESCRIPTION'] ?? null,
            'transaction_id' => $this->niubiz_response['dataMap']['TRANSACTION_ID'] ?? null,
            'action_code' => $this->niubiz_response['dataMap']['ACTION_CODE'] ?? null,
            'transaction_date' => $this->niubiz_response['dataMap']['TRANSACTION_DATE'] ?? null,
            'amount' => $this->niubiz_response['order']['amount'] ?? null,
            'currency' => $this->niubiz_response['order']['currency'] ?? 'PEN',
            'purchase_number' => $this->purchase_number,
            'brand' => $this->niubiz_response['dataMap']['BRAND'] ?? null,
            'card' => $this->niubiz_response['dataMap']['CARD'] ?? null,
            'error_message' => $this->error_message
        ];
    }
}
