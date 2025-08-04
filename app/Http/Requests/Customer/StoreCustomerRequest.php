<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'email' => 'required|string|max:255|email|unique:customers',
            'password' => 'required|string|confirmed',
            'id_document_type' => 'required|integer|exists:document_types,id',
            'document_number' => 'required|string',
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|string|max:9',
            'deparment' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string'
        ];
    }
}
