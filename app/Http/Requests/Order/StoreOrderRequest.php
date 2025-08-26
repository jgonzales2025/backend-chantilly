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
            'voucher_type' => 'required|string|in:BOLETA,FACTURA',
            'billing_data.ruc' => 'required_if:voucher_type,FACTURA|digits:11',
            'billing_data.razon_social' => 'required_if:voucher_type,FACTURA|string|max:255',
            'billing_data.tax_address' => 'required_if:voucher_type,FACTURA|string|max:255',
            'local_id' => 'required|integer|exists:locals,id',
            'subtotal' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'delivery_date' => 'nullable|date',

            // Para order items
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.cake_flavor_id' => 'nullable|exists:cake_flavors,id',
            'items.*.quantity' => 'required|integer',
            'items.*.unit_price' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
            'items.*.dedication_text' => 'nullable|string',
            'items.*.delivery_date' => 'nullable|date',
        ];
    }
}
