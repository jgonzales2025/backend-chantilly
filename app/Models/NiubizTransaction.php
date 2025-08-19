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
        'niubiz_request',
        'niubiz_response',
        'error_message',
        'retry_count',
        'last_retry_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'niubiz_request' => 'array',
        'niubiz_response' => 'array',
        'last_retry_at' => 'datetime',
        'status' => TransactionStatusEnum::class // Cast al enum
    ];

    // Relación con Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes usando enum
    public function scopePending($query)
    {
        return $query->where('status', TransactionStatusEnum::PENDING);
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', TransactionStatusEnum::SUCCESS);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', TransactionStatusEnum::FAILED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', TransactionStatusEnum::CANCELLED);
    }

    // Métodos auxiliares usando enum
    public function isSuccess()
    {
        return $this->status === TransactionStatusEnum::SUCCESS;
    }

    public function isFailed()
    {
        return $this->status === TransactionStatusEnum::FAILED;
    }

    public function isPending()
    {
        return $this->status === TransactionStatusEnum::PENDING;
    }

    public function isCancelled()
    {
        return $this->status === TransactionStatusEnum::CANCELLED;
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
            'action_code' => $this->niubiz_response['dataMap']['ACTION_CODE'] ?? null,
            'transaction_id' => $this->niubiz_response['dataMap']['TRANSACTION_ID'] ?? null,
            'transaction_date' => $this->niubiz_response['dataMap']['TRANSACTION_DATE'] ?? null,
            'card' => $this->niubiz_response['dataMap']['CARD'] ?? null,
            'brand' => $this->niubiz_response['dataMap']['BRAND'] ?? null,
            'amount' => $this->niubiz_response['order']['amount'] ?? null,
        ];
    }
}
