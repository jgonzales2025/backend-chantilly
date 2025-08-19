<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'short_description' => 'nullable|string',
            'large_description' => 'nullable|string',
            'product_type_id' => 'required|integer|exists:product_types,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
            'theme_id' => 'nullable|integer|exists:themes,id',
            'image_url' => 'nullable|string',
            'status' => 'required|boolean',
            'best_status' => 'required|boolean',
            'product_link' => 'nullable|string'
        ];
    }
}
