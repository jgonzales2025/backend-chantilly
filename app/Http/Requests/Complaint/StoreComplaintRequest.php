<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
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
            'number_complaint' => 'required|string|max:255|unique:complaints,number_complaint',
            'local_id' => 'required|exists:locals,id',
            'customer_name' => 'required|string|max:255',
            'customer_lastname' => 'required|string|max:255',
            'dni_ruc' => 'required|string|max:11',
            'address' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:9',
            'parent_data' => 'nullable|string',
            'well_hired' => 'required|in:Producto,Servicio',
            'amount' => 'required|numeric|min:0.00',
            'description' => 'required|string',
            'type_complaint' => 'required|in:Reclamo,Queja',
            'detail_complaint' => 'required|string',
            'order' => 'required|string|max:255',
            'date_complaint' => 'required|date',
            'path_evidence' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            'observations' => 'required|string',
            'path_customer_signature' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            'recaptcha_token' => 'required|string'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'number_complaint.required' => 'El número de reclamo es obligatorio.',
            'number_complaint.unique' => 'Este número de reclamo ya existe.',
            'number_complaint.max' => 'El número de reclamo no puede exceder los 255 caracteres.',
            
            'local_id.required' => 'Debe seleccionar un local.',
            'local_id.exists' => 'El local seleccionado no existe.',
            
            'customer_name.required' => 'El nombre del cliente es obligatorio.',
            'customer_name.max' => 'El nombre del cliente no puede exceder los 255 caracteres.',
            
            'customer_lastname.required' => 'El apellido del cliente es obligatorio.',
            'customer_lastname.max' => 'El apellido del cliente no puede exceder los 255 caracteres.',
            
            'dni_ruc.required' => 'El DNI/RUC es obligatorio.',
            'dni_ruc.max' => 'El DNI/RUC no puede exceder los 11 caracteres.',
            
            'department.required' => 'El departamento es obligatorio.',
            'department.max' => 'El departamento no puede exceder los 255 caracteres.',
            
            'province.required' => 'La provincia es obligatoria.',
            'province.max' => 'La provincia no puede exceder los 255 caracteres.',
            
            'district.required' => 'El distrito es obligatorio.',
            'district.max' => 'El distrito no puede exceder los 255 caracteres.',
            
            'address.required' => 'La dirección es obligatoria.',
            
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede exceder los 255 caracteres.',
            
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no puede exceder los 9 caracteres.',
            
            'well_hired.required' => 'Debe especificar si es un producto o servicio.',
            'well_hired.in' => 'Debe seleccionar entre Producto o Servicio.',
            
            'amount.required' => 'El monto es obligatorio.',
            'amount.integer' => 'El monto debe ser un número entero.',
            'amount.min' => 'El monto no puede ser negativo.',
            
            'description.required' => 'La descripción es obligatoria.',
            
            'type_complaint.required' => 'Debe especificar el tipo de reclamo.',
            'type_complaint.in' => 'Debe seleccionar entre Reclamo o Queja.',
            
            'detail_complaint.required' => 'El detalle del reclamo es obligatorio.',
            
            'order.required' => 'El número de pedido es obligatorio.',
            'order.max' => 'El número de pedido no puede exceder los 255 caracteres.',
            
            'date_complaint.required' => 'La fecha del reclamo es obligatoria.',
            'date_complaint.date' => 'Debe ingresar una fecha válida.',
            
            'path_evidence.image' => 'La evidencia debe ser una imagen.',
            'path_evidence.mimes' => 'La evidencia debe ser un archivo de tipo: jpeg, png, jpg, webp.',
            'path_evidence.max' => 'La evidencia no puede exceder los 2MB.',
            
            'observations.required' => 'Las observaciones son obligatorias.',
            
            'path_customer_signature.required' => 'La firma del cliente es obligatoria.',
            'path_customer_signature.image' => 'La firma debe ser una imagen.',
            'path_customer_signature.mimes' => 'La firma debe ser un archivo de tipo: jpeg, png, jpg, webp.',
            'path_customer_signature.max' => 'La firma no puede exceder los 2MB.',
        ];
    }
}
