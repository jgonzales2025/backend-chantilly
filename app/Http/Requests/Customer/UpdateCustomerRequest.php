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

    public function messages(): array
    {
        return [
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',

            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'id_document_type.required' => 'El tipo de documento es obligatorio.',
            'id_document_type.integer' => 'El tipo de documento debe ser un número entero.',
            'id_document_type.exists' => 'El tipo de documento seleccionado no es válido.',

            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.string' => 'El número de documento debe ser una cadena de texto.',

            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder 100 caracteres.',

            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.string' => 'El apellido debe ser una cadena de texto.',
            'lastname.max' => 'El apellido no debe exceder 100 caracteres.',

            'address.required' => 'La dirección es obligatoria.',
            'address.string' => 'La dirección debe ser una cadena de texto.',

            'phone.required' => 'El teléfono es obligatorio.',
            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no debe exceder 9 caracteres.',

            'department.string' => 'El departamento debe ser una cadena de texto.',
            'province.string' => 'La provincia debe ser una cadena de texto.',
            'district.string' => 'El distrito debe ser una cadena de texto.',
            'department_code.string' => 'El código de departamento debe ser una cadena de texto.',
            'province_code.string' => 'El código de provincia debe ser una cadena de texto.',
            'district_code.string' => 'El código de distrito debe ser una cadena de texto.',
        ];
    }
    
}
