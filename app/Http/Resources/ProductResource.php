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
            'short_description' => $this->short_description,
            'large_description' => $this->large_description,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'status' => $this->status,
            'best_status' => $this->best_status,
            'product_type_id' => $this->whenLoaded('productType'),
            'category_id' => $this->whenLoaded('category'),
            'theme_id' => $this->whenLoaded('theme'),
            'product_link' => $this->product_link
        ];
    }
}
