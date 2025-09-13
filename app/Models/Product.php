<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasImages;

    protected $fillable = [
        'cod_fab',
        'short_description',
        'large_description',
        'product_type_id',
        'category_id',
        'min_price',
        'max_price',
        'theme_id',
        'status',
        'best_status',
        'product_link'
    ];

    public $timestamps = false;

    protected $casts = [
        'status' => 'boolean',
        'best_status' => 'boolean'
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }

    // Filtros para productos
    public function scopeFilterProducts($query, $filters = [])
    {
        return $query
            ->when($filters['theme_id'] ?? false, function ($query, $themeId) {
                return $query->where('theme_id', $themeId);
            })
            ->when($filters['name'] ?? false, function ($query, $name) {
                return $query->where('short_description', 'LIKE', "%{$name}%");
            })
            ->when($filters['product_type_id'] ?? false, function ($query, $prodType) {
                return $query->where('product_type_id', $prodType);
            })
            ->when($filters['best_status'] ?? false, function ($query, $bestStatus) {
                return $query->where('best_status', $bestStatus);
            });
    }
}
