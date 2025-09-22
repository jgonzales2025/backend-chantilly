<?php

namespace App\Http\Requests\Banner;

use App\Models\Banner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBannerRequest extends FormRequest
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
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url',
            'status' => 'nullable|boolean'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (Banner::count() >= 12) {
                $validator->errors()->add(
                    'banners', 
                    'No se pueden crear más banners. Máximo permitido: 12 banners.'
                );
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'La imagen es obligatoria.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no debe superar los 2MB.',
            'link_url.url' => 'El enlace debe ser una URL válida.',
        ];
    }
}
