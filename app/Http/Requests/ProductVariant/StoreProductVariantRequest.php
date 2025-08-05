<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
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
            'cod_fab' => 'required|string',
            'product_id' => 'required|integer|exists:products,id',
            'description' => 'required|string',
            'portions' => 'required|string',
            'size_portion' => 'required|string',
            'price' => 'required|numeric',
            'hours' => 'nullable|integer',
            'sort' => 'nullable|integer'
        ];
    }
}
