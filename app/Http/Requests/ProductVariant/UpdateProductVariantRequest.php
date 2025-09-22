<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
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
        $rules = [
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|max:2048'
        ];
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'images.max' => 'No se pueden subir más de 3 imágenes por producto.',
            'images.*.image' => 'El archivo debe ser una imagen válida.',
            'images.*.max' => 'La imagen no debe pesar más de 2MB.',
            '*.image' => 'El archivo debe ser una imagen válida.',
            '*.max' => 'La imagen no debe pesar más de 2MB.'
        ];
    }
}
