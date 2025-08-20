<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = "tg_tabdep";

    public $timestamps = false;
    
    protected $fillable = [
        'coddep',
        'nomdep',
        'st'
    ];
}
