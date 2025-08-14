<?php

namespace App\Http\Requests\CakeFlavor;

use Illuminate\Foundation\Http\FormRequest;

class StoreCakeFlavorRequest extends FormRequest
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
            'name' => 'required|string',
            'status' => 'required|boolean',
            'filling_id' => 'required|array|exists:fillings,id',
        ];
    }
}
