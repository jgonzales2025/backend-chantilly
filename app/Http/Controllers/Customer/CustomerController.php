<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
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
            'district' => $validatedData['district']
        ]);

        return new JsonResponse([
            'message' => 'Cliente registrado con éxito',
            "customer" => $customer
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
