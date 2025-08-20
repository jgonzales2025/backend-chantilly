<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = "tg_tabpro";

    public $timestamps = false;
    
    protected $fillable = [
        'coddep',
        'codpro',
        'nompro',
        'ST'
    ];
}
