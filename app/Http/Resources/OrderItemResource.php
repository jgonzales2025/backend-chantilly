<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product' => $this->product,
            'product_variant' => $this->productVariant,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'subtotal' => $this->subtotal,
            'dedication_text' => $this->dedication_text,
            'delivery_date' => $this->delivery_date,
            'image_url' => optional($this->product)->image_url ?? optional($this->productVariant)->image_url,
        ];
    }
}
