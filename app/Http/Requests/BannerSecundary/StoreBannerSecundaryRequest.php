<?php

namespace App\Http\Requests\BannerSecundary;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerSecundaryRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'image_movil' => 'nullable|image|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'image.image' => 'El archivo debe ser una imagen válida.',
            'image_movil.image' => 'El archivo debe ser una imagen válida.',
            'image.max' => 'La imagen no debe superar los 2MB.',
            'image_movil.max' => 'La imagen móvil no debe superar los 2MB.'
        ];
    }
}
