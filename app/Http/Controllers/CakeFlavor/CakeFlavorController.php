<?php

namespace App\Http\Controllers\CakeFlavor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CakeFlavor\StoreCakeFlavorRequest;
use App\Models\CakeFlavor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CakeFlavorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cakeFlavors = CakeFlavor::all();

        if($cakeFlavors->isEmpty()){
            return new JsonResponse(['message' => 'No hay cakes registrados']);
        }

        return new JsonResponse($cakeFlavors, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCakeFlavorRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $cakeFlavor = CakeFlavor::create($validatedData);

        return new JsonResponse([
            'message' => 'Cake creado exitosamente',
            'cake' => $cakeFlavor
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
