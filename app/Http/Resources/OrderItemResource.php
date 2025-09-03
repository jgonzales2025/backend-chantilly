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
            'image_url' => $this->getImageUrl(),
        ];
    }

    /**
     * Obtener la URL de la imagen principal
     */
    private function getImageUrl()
    {
        // Primero intentar obtener la imagen del product_variant si existe
        if ($this->productVariant && $this->productVariant->primaryImage) {
            return asset('storage/' . $this->productVariant->primaryImage->path_url);
        }
        
        // Si no hay variant o no tiene imagen, usar la del producto
        if ($this->product && $this->product->primaryImage) {
            return asset('storage/' . $this->product->primaryImage->path_url);
        }
        
        // Si no hay imagen principal, tomar la primera imagen disponible
        if ($this->productVariant && $this->productVariant->images->isNotEmpty()) {
            return asset('storage/' . $this->productVariant->images->first()->path_url);
        }
        
        if ($this->product && $this->product->images->isNotEmpty()) {
            return asset('storage/' . $this->product->images->first()->path_url);
        }
        
        // Si no hay imágenes, retornar null o una imagen por defecto
        return null;
    }
}
