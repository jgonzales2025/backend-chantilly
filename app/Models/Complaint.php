<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $fillable = [
        'number_complaint',
        'local_id',
        'customer_name',
        'customer_lastname',
        'dni_ruc',
        'department',
        'province',
        'district',
        'address',
        'email',
        'phone',
        'parent_data',
        'well_hired',
        'amount',
        'description',
        'type_complaint',
        'detail_complaint',
        'order',
        'date_complaint',
        'path_evidence',
        'observations',
        'path_customer_signature'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    public function getImageEvidenceAttribute()
    {
        return $this->path_evidence
            ? asset('storage/' . $this->path_evidence)
            : null;
    }

    public function getImageSignatureAttribute()
    {
        return $this->path_customer_signature
            ? asset('storage/' . $this->path_customer_signature)
            : null;
    }
}
