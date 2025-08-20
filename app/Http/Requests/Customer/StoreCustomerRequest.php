<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'document_number' => [
                'required',
                'string',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('id_document_type', $this->id_document_type);
                }),
                function ($attribute, $value, $fail) {
                    $typeId = $this->input('id_document_type');

                    switch ($typeId) {
                        case 1: // DNI
                            if (!preg_match('/^[0-9]{8}$/', $value)) {
                                $fail("El campo {$attribute} debe tener exactamente 8 dígitos para un DNI.");
                            }
                            break;

                        case 2: // Carné de extranjería
                            if (!preg_match('/^[0-9]{8}$/', $value)) {
                                $fail("El campo {$attribute} debe tener 8 dígitos para un Carné de Extranjería.");
                            }
                            break;

                        case 3: // PTP
                            if (!preg_match('/^[0-9]{9}$/', $value)) {
                                $fail("El campo {$attribute} debe tener 9 dígitos para un PTP.");
                            }
                            break;

                        case 4: // Pasaporte
                            if (!preg_match('/^[A-Z0-9]{9}$/i', $value)) {
                                $fail("El campo {$attribute} debe tener 9 caracteres alfanuméricos para un Pasaporte.");
                            }
                            break;
                    }
                }
            ],
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|string|max:9',
            'department' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'department_code' => 'nullable|string',
            'province_code' => 'nullable|string',
            'district_code' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.max' => 'El correo electrónico no debe exceder 255 caracteres.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'id_document_type.required' => 'El tipo de documento es obligatorio.',
            'id_document_type.integer' => 'El tipo de documento debe ser un número entero.',
            'id_document_type.exists' => 'El tipo de documento seleccionado no es válido.',

            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.string' => 'El número de documento debe ser una cadena de texto.',
            'document_number.unique' => 'El número de documento ya está registrado.',

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

            'department.required' => 'El departamento es obligatorio.',
            'department.string' => 'El departamento debe ser una cadena de texto.',

            'province.required' => 'La provincia es obligatoria.',
            'province.string' => 'La provincia debe ser una cadena de texto.',

            'district.required' => 'El distrito es obligatorio.',
            'district.string' => 'El distrito debe ser una cadena de texto.',

            'department_code.string' => 'El código de departamento debe ser una cadena de texto.',
            'province_code.string' => 'El código de provincia debe ser una cadena de texto.',
            'district_code.string' => 'El código de distrito debe ser una cadena de texto.',
        ];
    }
}
