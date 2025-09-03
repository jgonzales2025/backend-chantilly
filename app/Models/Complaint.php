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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($complaint) {
            if (empty($complaint->number_complaint)) {
                $complaint->number_complaint = self::generateComplaintNumber();
            }
        });
    }

    public static function generateComplaintNumber()
    {
        $year = date('Y');
        $prefix = 'CHA';
        
        // Obtener el último número de queja del año actual
        $lastComplaint = self::where('number_complaint', 'like', "{$prefix}-%{$year}")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastComplaint) {
            // Extraer el número secuencial del último registro
            preg_match('/CHA-(\d+)-' . $year . '/', $lastComplaint->number_complaint, $matches);
            $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Formatear con ceros a la izquierda (5 dígitos)
        $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$formattedNumber}-{$year}";
    }

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
