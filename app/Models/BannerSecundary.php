<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerSecundary extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'image_path_movil',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? asset("storage/{$this->image_path}")
            : null;
    }

    public function getImageUrlMovilAttribute(): ?string
    {
        return $this->image_path_movil
            ? asset("storage/{$this->image_path_movil}")
            : null;
    }
}
