<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'subtotal',
        'total',
        'order_date',
        'delivery_date',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
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

}
