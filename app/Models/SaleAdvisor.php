<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleAdvisor extends Model
{
    protected $fillable = ['name', 'phone'];

    protected $hidden = ['created_at', 'updated_at'];
}
