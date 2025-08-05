<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CakeFlavor extends Model
{
    protected $fillable = ['name', 'status', 'filling_id'];

    public $timestamps = false;

    protected $casts = [
        'status' => 'boolean'
    ];

    public function filling(): BelongsTo
    {
        return $this->belongsTo(Filling::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
