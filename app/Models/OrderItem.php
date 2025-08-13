<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'cake_flavor_id',
        'quantity',
        'unit_price',
        'subtotal',
        'dedication_text'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function cakeFlavor(): BelongsTo
    {
        return $this->belongsTo(CakeFlavor::class);
    }
}
