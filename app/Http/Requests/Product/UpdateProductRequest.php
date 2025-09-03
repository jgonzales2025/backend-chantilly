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
        $rules = [
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp'
        ];
        
        // También validar formatos con índices numéricos
        foreach ($this->allFiles() as $key => $file) {
            if (preg_match('/^images\.\d+$/', $key)) {
                $rules[$key] = 'image|mimes:jpg,jpeg,png,webp';
            }
        }
        
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
            'images.*.mimes' => 'La imagen debe ser de tipo: jpg, jpeg, png, webp o gif.',
            'images.*.max' => 'La imagen no debe pesar más de 5MB.',
            '*.image' => 'El archivo debe ser una imagen válida.',
            '*.mimes' => 'La imagen debe ser de tipo: jpg, jpeg, png, webp o gif.',
            '*.max' => 'La imagen no debe pesar más de 5MB.'
        ];
    }

}
