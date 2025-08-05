<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Para orders
            'customer_id' => 'required|integer|exists:customers,id',
            'subtotal' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'delivery_date' => 'required|date',
            'status' => 'required|boolean',

            // Para order items
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.cake_flavor_id' => 'required|exists:cake_flavors,id',
            'items.*.quantity' => 'required|integer',
            'items.*.unit_price' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
            'items.*.dedication_text' => 'nullable|string'
        ];
    }
}
