<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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
            'number_complaint' => $this->number_complaint,
            'local' => $this->local,
            'customer_name' => $this->customer_name,
            'customer_lastname' => $this->customer_lastname,
            'dni_ruc' => $this->dni_ruc,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'parent_data' => $this->parent_data,
            'well_hired' => $this->well_hired,
            'amount' => $this->amount,
            'description' => $this->description,
            'type_complaint' => $this->type_complaint,
            'detail_complaint' => $this->detail_complaint,
            'order' => $this->order,
            'date_complaint' => $this->date_complaint,
            'image_evidence' => $this->image_evidence,
            'observations' => $this->observations,
            'image_signature' => $this->image_signature,
        ];
    }
}
