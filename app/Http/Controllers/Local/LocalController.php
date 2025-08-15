<?php

namespace App\Http\Controllers\Local;

use App\Http\Controllers\Controller;
use App\Http\Requests\Local\IndexLocationRequest;
use App\Http\Requests\Local\StoreLocalRequest;
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

        return new JsonResponse($locals, 200);
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

        return new JsonResponse($locals, 200);
    }

    /**
     * Crear un nuevo local.
     */
    public function store(StoreLocalRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $local = Local::create($validatedData);

        return new JsonResponse($local, 201);
    }
}
