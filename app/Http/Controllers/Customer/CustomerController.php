<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
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

        $customer = Customer::create([
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'id_document_type' => $validatedData['id_document_type'],
            'document_number' => $validatedData['document_number'],
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'deparment' => $validatedData['deparment'],
            'province' => $validatedData['province'],
            'district' => $validatedData['district'],
            'deparment_code' => $validatedData['deparment_code'],
            'province_code' => $validatedData['province_code'],
            'district_code' => $validatedData['district_code']
        ]);

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
            'email' => $validatedData['email'],
            'document_number' => $validatedData['document_number'],
            'name' => $validatedData['name'],
            'lastname' => $validatedData['lastname'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone']
        ];

        if (array_key_exists('password', $validatedData))
        {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        if (array_key_exists('deparment', $validatedData))
        {
            $updateData['deparment'] = $validatedData['deparment'];
            $updateData['province'] = $validatedData['province'];
            $updateData['district'] = $validatedData['district'];
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
