<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreRequest extends FormRequest
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
            'banners' => 'required|array|max:12',
            'banners.*.image' => 'nullable|image|max:2048',
            'banners.*.link_url' => 'nullable|url',
            'banners.*.status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'banners.required' => 'El campo de banners es obligatorio.',
            'banners.array' => 'El campo de banners debe ser un arreglo.',
            'banners.max' => 'No se pueden subir más de 12 banners a la vez.',
            'banners.*.image.image' => 'Cada banner debe ser una imagen válida.',
            'banners.*.image.max' => 'Cada imagen no debe superar los 2MB.',
            'banners.*.link_url.url' => 'Cada URL de enlace debe ser una URL válida.',
            'banners.*.status.boolean' => 'El estado de cada banner debe ser verdadero o falso.',
        ];
    }
}
