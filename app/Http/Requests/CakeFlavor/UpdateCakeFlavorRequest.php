<?php

namespace App\Http\Requests\CakeFlavor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCakeFlavorRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'status' => 'sometimes|boolean',
            'filling_id' => 'sometimes|integer|exists:fillings,id'
        ];
    }
}
