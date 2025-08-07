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
            'cod_fav' => $this->cod_fab,
            'description' => $this->description,
            'portions' => $this->portions,
            'size_portion' => $this->size_portion,
            'price' => $this->price,
            'hours' => $this->hours,
            'sort' => $this->sort,
            'image' => $this->image ? asset('/storage'. $this->image) : null,
            'product_id' => $this->whenLoaded('product'),
        ];
    }
}
