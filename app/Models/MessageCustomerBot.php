<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageCustomerBot extends Model
{
    protected $fillable = ['session_id', 'message'];

}
