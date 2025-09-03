<?php

namespace App\Http\Requests\BannerSecundary;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerSecundaryRequest extends FormRequest
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
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_movil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
