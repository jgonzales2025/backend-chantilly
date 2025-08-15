<?php

namespace App\Http\Controllers\CakeFlavor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CakeFlavor\StoreCakeFlavorRequest;
use App\Http\Requests\CakeFlavor\UpdateCakeFlavorRequest;
use App\Models\CakeFlavor;
use Illuminate\Http\JsonResponse;

class CakeFlavorController extends Controller
{
    /**
     * Listar sabores de cake.
     */
    public function index(): JsonResponse
    {
        $cakeFlavors = CakeFlavor::with('fillings')->get();

        if($cakeFlavors->isEmpty()){
            return new JsonResponse(['message' => 'No hay cakes registrados']);
        }

        return new JsonResponse($cakeFlavors, 200);
    }

    /**
     * Crear sabor de cake.
     */
    public function store(StoreCakeFlavorRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $cakeFlavor = CakeFlavor::create([
            'name' => $validatedData['name'],
            'status' => $validatedData['status']
        ]);

        $cakeFlavor->fillings()->attach($validatedData['filling_id']);

        return new JsonResponse([
            'message' => 'Cake creado exitosamente',
            'cake' => $cakeFlavor->load('fillings')
        ], 201);
    }

    /**
     * Buscar sabor por id.
     */
    public function show($id): JsonResponse
    {
        $cakeFlavor = CakeFlavor::with('fillings')->find($id);

        if (!$cakeFlavor) {
            return new JsonResponse(['message' => 'Cake no encontrado'], 404);
        }

        return new JsonResponse($cakeFlavor, 200);
    }

    /**
     * Actualizar sabor por id.
     */
    public function update(UpdateCakeFlavorRequest $request, $id)
    {
        $cakeFlavor = CakeFlavor::find($id);

        if (!$cakeFlavor) {
            return new JsonResponse(['message' => 'Cake no encontrado'], 404);
        }

        $validatedData = $request->validated();

        $cakeFlavor->update([
            'name' => $validatedData['name'],
            'status' => $validatedData['status']
        ]);

        if (isset($validatedData['filling_id'])) {
            $cakeFlavor->fillings()->sync($validatedData['filling_id']);
        }

        return new JsonResponse([
            'message' => 'Cake actualizado con éxito',
            'cake' => $cakeFlavor->load('fillings')
        ], 200);
    }

    /**
     * Eliminar sabor por id.
     */
    public function destroy($id): JsonResponse
    {
        $cakeFlavor = CakeFlavor::find($id);

        if (!$cakeFlavor) {
            return new JsonResponse(['message' => 'Cake no encontrado'], 404);
        }

        $cakeFlavor->delete();

        return new JsonResponse(['message' => 'Cake eliminado con éxito'], 200);
    }
}
