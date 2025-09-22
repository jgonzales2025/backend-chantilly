<?php

namespace App\Models;

use App\Enum\PaymentStatusEnum;
use App\Enum\TransactionStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'status_id',
        'payment_method',
        'payment_status', 
        'paid_at',
        'delivery_date'
    ];

    protected $casts = [
        'billing_data' => 'array',
        'paid_at' => 'datetime',
        'order_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = ['created_at','updated_at'];

    /* public function getOrderDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    } */

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    public function pointHistories(): HasOne
    {
        return $this->hasOne(PointHistory::class);
    }

    // Relación con transacciones Niubiz
    public function niubizTransactions()
    {
        return $this->hasMany(NiubizTransaction::class);
    }

    // Marcar como pagada usando enum
    public function markAsPaid($paymentMethod = 'niubiz')
    {
        $this->update([
            'payment_method' => $paymentMethod,
            'payment_status' => 'Pagado',
            'paid_at' => now()
        ]);
    }

    // Marcar como pago fallido
    public function markPaymentAsFailed()
    {
        $this->update([
            'payment_status' => 'Fallido'
        ]);
    }

    // Scope para filtrar órdenes
    public function scopeFilterOrders($query, $filters = [])
    {
        return $query->when($filters['customer_id'] ?? false, function ($query, $customerId) {
                return $query->where('customer_id', $customerId);
            })
            ->when($filters['order_number'] ?? false, function ($query, $orderNumber) {
                return $query->where('order_number', $orderNumber);
            })
            ->when($filters['date_filter'] ?? false, function ($query, $dateFilter) {
                switch ($dateFilter) {
                    case 'ultimos_30_dias':
                        return $query->where('order_date', '>=', now()->subDays(30)->startOfDay());
                    case 'ultimos_3_meses':
                        return $query->where('order_date', '>=', now()->subMonths(3)->startOfDay());
                    case 'ultimos_6_meses':
                        return $query->where('order_date', '>=', now()->subMonths(6)->startOfDay());
                    case '2025':
                        return $query->whereYear('order_date', 2025);
                    default:
                        return $query;
                }
            });
    }

}
