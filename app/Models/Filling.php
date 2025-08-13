<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filling extends Model
{
    protected $fillable = ['name', 'status'];

    public $timestamps = false;

    protected $casts = [
        'status' => 'boolean'
    ];

    public function cakeflavors(): HasMany
    {
        return $this->hasMany(CakeFlavor::class);
    }
}
