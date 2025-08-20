<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
        $customerId = $this->route('id');
        $rules = [
            'email' => ['nullable', 'string', 'email', Rule::unique('customers')->ignore($customerId)],
            'id_document_type' => 'required|integer|exists:document_types,id',
            'document_number' => 'required|string',
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|string|max:9',
            
        ];

        if ($this->filled('password')) {
            $rules['password'] = ['string', 'confirmed'];
        }

        if ($this->filled('department')) {
            $rules['department'] = ['nullable', 'string'];
            $rules['province'] = ['nullable', 'string'];
            $rules['district'] = ['nullable', 'string'];
            $rules['department_code'] = ['nullable', 'string'];
            $rules['province_code'] = ['nullable', 'string'];
            $rules['district_code'] = ['nullable', 'string'];
        }


        return $rules;
    }
    
}
