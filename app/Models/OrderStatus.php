<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatus extends Model
{
    protected $fillable = ['name', 'order', 'order_backup', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
