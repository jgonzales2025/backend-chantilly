<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'voucher_type' => $this->voucher_type,
            'billing_data' => $this->billing_data,
            'local' => $this->local,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'order_date' => $this->order_date,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'order_number' => $this->order_number,
            'delivery_date' => $this->delivery_date,
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}
