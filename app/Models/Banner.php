<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'image_path',
        'link_url',
        'status',
        'display_order'
    ];

    protected $casts = [
        'status' => 'boolean'
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