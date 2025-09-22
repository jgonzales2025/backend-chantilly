<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'product_id' => $this->product_id,
            'description' => $this->description,
            'portions' => $this->portions,
            'price' => $this->price,
            'hours' => $this->hours,
            'status' => $this->status,
            'is_redemption' => $this->is_redemption,
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->path_url),
                        'is_primary' => $image->is_primary
                    ];
                });
            }),
            'product' => $this->whenLoaded('product'),
        ];
    }
}
