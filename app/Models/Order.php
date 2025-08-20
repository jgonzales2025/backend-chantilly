<?php

namespace App\Models;

use App\Enum\PaymentStatusEnum;
use App\Enum\TransactionStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'voucher_type',
        'billing_data',
        'local_id',
        'subtotal',
        'total',
        'order_date',
        'status',
        'payment_method',
        'payment_status', 
        'paid_at'
    ];

    protected $casts = [
        'status' => 'boolean',
        'billing_data' => 'array',
        'payment_status' => PaymentStatusEnum::class,
        'paid_at' => 'datetime'
    ];

    protected $hidden = ['created_at','updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_date = now();
        });
    }

    public function getOrderDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    // Relación con transacciones Niubiz
    public function niubizTransactions()
    {
        return $this->hasMany(NiubizTransaction::class);
    }

    // Obtener la última transacción Niubiz
    public function lastNiubizTransaction()
    {
        return $this->hasOne(NiubizTransaction::class)->latest();
    }

    // Obtener transacción exitosa de Niubiz
    public function successfulNiubizTransaction()
    {
        return $this->hasOne(NiubizTransaction::class)
                    ->where('status', TransactionStatusEnum::SUCCESS);
    }

    // Verificar si la orden está pagada usando enum
    public function isPaid()
    {
        return $this->payment_status === PaymentStatusEnum::PAID;
    }

    // Marcar como pagada usando enum
    public function markAsPaid($paymentMethod = 'niubiz')
    {
        $this->update([
            'payment_method' => $paymentMethod,
            'payment_status' => PaymentStatusEnum::PAID,
            'paid_at' => now()
        ]);
    }

    // Marcar como pago fallido
    public function markPaymentAsFailed()
    {
        $this->update([
            'payment_status' => PaymentStatusEnum::FAILED
        ]);
    }

    // Scope para órdenes pagadas
    public function scopePaid($query)
    {
        return $query->where('payment_status', PaymentStatusEnum::PAID);
    }

    // Scope para órdenes con pago pendiente
    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', PaymentStatusEnum::PENDING);
    }

}
