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
            'department' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:9',
            'parent_data' => 'nullable|string',
            'well_hired' => 'required|in:Producto,Servicio',
            'amount' => 'required|integer|min:0',
            'description' => 'required|string',
            'type_complaint' => 'required|in:Reclamo,Queja',
            'detail_complaint' => 'required|string',
            'order' => 'required|string|max:255',
            'date_complaint' => 'required|date',
            'path_evidence' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            'observations' => 'required|string',
            'path_customer_signature' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ];
    }
}
