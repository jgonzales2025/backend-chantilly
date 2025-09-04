<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasImages;

    protected $fillable = [
        'product_id',
        'description',
        'portions',
        'size_portion',
        'price',
        'hours',
        'sort'
    ];

    public $timestamps = false;

    protected $hidden = ['created_at', 'updated_at'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image
            ? asset("storage/{$this->image}")
            : null;
    }

}
