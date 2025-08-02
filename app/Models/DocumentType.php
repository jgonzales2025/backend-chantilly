<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function customers() : HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
