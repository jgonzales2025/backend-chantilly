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
}
