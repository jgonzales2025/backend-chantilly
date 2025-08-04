<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'business_name',
        'ruc',
        'number_whatsapp',
        'number_whatsapp1',
        'about us',
        'facebook',
        'instagram',
        'twitter',
        'tiktok',
        'logo_header',
        'logo_footer'
    ];

    public function locals(): HasMany
    {
        return $this->hasMany(Local::class);
    }

}
