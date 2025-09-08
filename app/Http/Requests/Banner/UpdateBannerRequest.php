<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'link_url' => 'nullable|url|max:255',
            'status' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser un archivo de tipo: jpg, jpeg, png, webp.',
            'link_url.url' => 'El enlace debe ser una URL válida.',
            'link_url.max' => 'El enlace no debe exceder los 255 caracteres.',
            'status.boolean' => 'El estado debe ser verdadero o falso.',
            'display_order.integer' => 'El orden de visualización debe ser un número entero.',
            'display_order.min' => 'El orden de visualización no puede ser negativo.'
        ];
    }
}
