<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'company_id'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
