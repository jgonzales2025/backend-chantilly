<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CakeFlavor extends Model
{
    protected $fillable = ['name', 'status'];

    protected $hidden = ['pivot'];

    public $timestamps = false;

    protected $casts = [
        'status' => 'boolean'
    ];

    public function fillings(): BelongsToMany
    {
        return $this->belongsToMany(Filling::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
