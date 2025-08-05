<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'lastname',
        'id_document_type',
        'document_number',
        'email',
        'password',
        'address',
        'phone',
        'deparment',
        'province',
        'district'
    ];

    protected $hidden = ['created_at', 'updated_at', 'password'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function document() : BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
