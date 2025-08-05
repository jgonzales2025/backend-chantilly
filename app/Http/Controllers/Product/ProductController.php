<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Mostrar productos.
     */
    public function index(Request $request): JsonResponse
    {
        $themeId = $request->query('theme_id');
        $products = Product::when($themeId, function ($query) use ($themeId){
            $query->where('theme_id', $themeId);
        })->paginate(8);
        
        if($products->isEmpty()){
            return new JsonResponse(['message' => 'No hay productos registrados']);
        }

        return new JsonResponse([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
            'next_page_url' => $products->nextPageUrl(),
            'prev_page_url' => $products->previousPageUrl()
        ]);
    }

    /**
     * Registrar producto.
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();
        $product = Product::create($validatedData);
        
        return new JsonResponse([
            'message' => 'Producto registrado con éxito',
            'product' => $product
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
