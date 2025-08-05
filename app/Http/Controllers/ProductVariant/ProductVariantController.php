<?php

namespace App\Http\Controllers\ProductVariant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariant\StoreProductVariantRequest;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $productVariants = ProductVariant::all();

        if ($productVariants->isEmpty()){
            return new JsonResponse(['message' => 'No hay variantes de productos registrados']);
        }

        return new JsonResponse($productVariants, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductVariantRequest $request)
    {
        $validatedData = $request->validated();
        $productVariant = ProductVariant::create($validatedData);

        return new JsonResponse([
            'message' => 'Variante de producto creado exitosamente',
            'variant' => $productVariant
        ]);
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
