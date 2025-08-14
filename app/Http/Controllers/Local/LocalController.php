<?php

namespace App\Http\Controllers\Local;

use App\Http\Controllers\Controller;
use App\Models\Local;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocalController extends Controller
{

    /**
     * Listar locales ordenados según distancia del cliente
     */
    public function indexByLocation(Request $request): JsonResponse
    {
        $locals = Local::nearestTo($request->latitude, $request->longitude)->get();

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
}
