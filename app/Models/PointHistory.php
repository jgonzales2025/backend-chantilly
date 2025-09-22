<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointHistory extends Model
{
    protected $fillable = [
        'order_id',
        'order_date',
        'sale_amount',
        'conversion_rate',
        'points_earned',
        'point_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
