<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $fillable = ['name', 'image_url'];

    public $timestamps = false;

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
