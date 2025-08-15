<?php

namespace App\Http\Controllers\Local;

use App\Http\Controllers\Controller;
use App\Http\Requests\Local\IndexLocationRequest;
use App\Http\Requests\Local\StoreLocalRequest;
use App\Http\Resources\LocalResource;
use App\Models\Local;
use Illuminate\Http\JsonResponse;

class LocalController extends Controller
{

    /**
     * Listar locales ordenados según distancia del cliente
     */
    public function indexByLocation(IndexLocationRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $locals = Local::nearestTo($validatedData['latitud'], $validatedData['longitud'])->get();

        if ($locals->isEmpty()) {
            return new JsonResponse(['message' => 'No hay locales registrados'], 404);
        }

        return new JsonResponse(LocalResource::collection($locals), 200);
    }

    /**
     * Listar todos los locales.
     */
    public function index(): JsonResponse
    {
        $locals = Local::all();

        if ($locals->isEmpty()) {
            return new JsonResponse(['message' => 'No hay locales registrados'], 404);
        }

        return new JsonResponse(LocalResource::collection($locals), 200);
    }

    /**
     * Crear un nuevo local.
     */
    public function store(StoreLocalRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('locals', 'public');

            $validatedData['image'] = $path;
        }

        $local = Local::create($validatedData);

        return new JsonResponse(new LocalResource($local), 201);
    }

    /**
     * Eliminar un local.
     */
    public function destroy($id): JsonResponse
    {
        $local = Local::find($id);

        if (!$local) {
            return new JsonResponse(['message' => 'Local no encontrado'], 404);
        }

        $local->delete();

        return new JsonResponse(['message' => 'Local eliminado correctamente'], 200);
    }
}
