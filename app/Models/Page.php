<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'name',
        'link_view',
        'orden',
        'status',
    ];

    public $timestamps = false;

    protected $casts =[
        'status' => 'boolean'
    ];
}
