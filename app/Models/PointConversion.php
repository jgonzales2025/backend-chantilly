<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PointConversion extends Model
{
    protected $fillable = [
        'soles_to_points',
        'points_to_soles',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
