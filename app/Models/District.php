<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = "tg_tabdis";

    public $timestamps = false;

    protected $fillable = [
        'coddep',
        'codpro',
        'coddis',
        'nomdis',
        'st'
    ];
}
