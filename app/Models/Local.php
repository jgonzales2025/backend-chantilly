<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Local extends Model
{
    protected $fillable = [
        'name',
        'image',
        'address',
        'department',
        'province',
        'district',
        'start_time',
        'end_time',
        'link_local',
        'latitud',
        'longitud',
        'frame_google',
        'company_id'
    ];

    public $timestamps = false;

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeNearestTo($query, $latitud, $longitud)
    {
        return $query->select('*')
            ->selectRaw("ROUND((
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitud)) *
                    cos(radians(longitud) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitud))
                )
            ), 2) AS distance", [$latitud, $longitud, $latitud])
            ->orderBy('distance');
    }

}
