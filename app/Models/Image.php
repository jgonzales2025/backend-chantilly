<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'path_url',
        'is_primary',
        'sort_order',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * Obtener URL completa de la imagen
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path_url);
    }
}
