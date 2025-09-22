<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cod_fab' => $this->cod_fab,
            'short_description' => $this->short_description,
            'large_description' => $this->large_description,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->url,
                        'is_primary' => $image->is_primary,
                        'sort_order' => $image->sort_order
                    ];
                });
            }),
            'status' => $this->status,
            'best_status' => $this->best_status,
            'product_type_id' => $this->whenLoaded('productType'),
            'category_id' => $this->whenLoaded('category'),
            'theme_id' => $this->whenLoaded('theme'),
            'product_link' => $this->product_link,
            'is_redemption' => $this->is_redemption,
        ];
    }
}
