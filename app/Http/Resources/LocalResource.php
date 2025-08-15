<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocalResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'address' => $this->address,
            'department' => $this->department,
            'province' => $this->province,
            'district' => $this->district,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'link_local' => $this->link_local,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'company_id' => $this->company_id,
            'distance' => $this->distance
        ];
    }
}
