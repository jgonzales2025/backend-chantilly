<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filling extends Model
{
    protected $fillable = ['name', 'status'];

    public $timestamps = false;

    protected $casts = [
        'status' => 'boolean'
    ];

    protected $hidden = ['pivot'];

    public function cakeflavors(): BelongsToMany
    {
        return $this->belongsToMany(CakeFlavor::class);
    }
}
