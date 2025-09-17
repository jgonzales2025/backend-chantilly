<?php

namespace App\Http\Controllers\ProductType;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\JsonResponse;

class ProductTypeController extends Controller
{
    /**
     * Listar tipos de productos.
     */
    public function index(): JsonResponse
    {
        $productTypes = ProductType::all();

        if ($productTypes->isEmpty()){
            return new JsonResponse(['message' => 'No hay tipos de productos registrados']);
        }

        return new JsonResponse($productTypes);
    }
}
