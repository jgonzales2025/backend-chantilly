<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\RecaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Services\SmsService;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{

    protected $smsService, $recaptchaService;

    public function __construct(SmsService $smsService, RecaptchaService $recaptchaService)
    {
        $this->smsService = $smsService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Listar clientes.
     */
    public function index() : JsonResponse
    {
        $customers = Customer::all();

        if ($customers->isEmpty())
        {
            return new JsonResponse(['message' => 'No hay clientes registrados']);
        }

        return new JsonResponse($customers, 200);
    }

    /**
     * Registrar cliente.
     */
    public function store(StoreCustomerRequest $request)
    {
        $validatedData = $request->validated();

        // Validar el token en Google
        $isValid = $this->recaptchaService->validateToken($validatedData['recaptcha_token']);

        if (!$isValid) {
            throw ValidationException::withMessages([
                'recaptcha' => 'La validación de reCAPTCHA falló.',
            ]);
        }


        $customer = Customer::create([
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'id_document_type' => $validatedData['id_document_type'],
            'document_number' => $validatedData['document_number'],
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'department' => $validatedData['department'],
            'province' => $validatedData['province'],
            'district' => $validatedData['district'],
            'department_code' => $validatedData['department_code'],
            'province_code' => $validatedData['province_code'],
            'district_code' => $validatedData['district_code'],
        ]);
        $phone = '+51' . ltrim($customer->phone, '0');

        $this->smsService->sendWelcomeSms($phone, $customer->name);

        return new JsonResponse([
            'message' => 'Cliente registrado con éxito',
            "customer" => $customer
        ], 201);
    }

    /**
     * Mostrar cliente por id.
     */
    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Cliente no encontrado'], 404);
        }

        return new JsonResponse($customer, 200);
    }

    /**
     * Actualizar cliente.
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Cliente no encontrado'], 404);
        }

        $validatedData = $request->validated();
        $updateData = [
            'email' => $validatedData['email'] ?? $customer->email,
            'document_number' => $validatedData['document_number'] ?? $customer->document_number,
            'name' => $validatedData['name'] ?? $customer->name,
            'lastname' => $validatedData['lastname'] ?? $customer->lastname,
            'address' => $validatedData['address'] ?? $customer->address,
            'phone' => $validatedData['phone'] ?? $customer->phone
        ];

        if (array_key_exists('password', $validatedData))
        {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        if (array_key_exists('department', $validatedData))
        {
            $updateData['department'] = $validatedData['department'];
            $updateData['province'] = $validatedData['province'];
            $updateData['district'] = $validatedData['district'];
            $updateData['department_code'] = $validatedData['department_code'];
            $updateData['province_code'] = $validatedData['province_code'];
            $updateData['district_code'] = $validatedData['district_code'];
        }

        $customer->update($updateData);

        return new JsonResponse(['message' => 'Cliente actualizado con éxito', 'customer' => $customer], 200);
    }

    /**
     * Eliminar cliente.
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Cliente no encontrado'], 404);
        }

        $customer->delete();

        return new JsonResponse(['message' => 'Cliente eliminado con éxito'], 200);
    }
}
