<?php

namespace App\Http\Controllers\ProductVariant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\ProductVariant\StoreProductVariantRequest;
use App\Http\Resources\ProductVariantResource;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
    /**
     * Listar variantes de productos.
     */
    public function index(Request $request): JsonResponse
    {
        $name = $request->query('name');

        $productVariants = ProductVariant::with('product', 'product.category', 'product.productType')
        ->when($name, function($query) use ($name){
            $query->where('description', 'LIKE', "%$name%");
        })
        ->paginate(8);

        if ($productVariants->isEmpty()){
            return new JsonResponse(['message' => 'No hay variantes de productos registrados']);
        }

        return new JsonResponse([
            'data' => ProductVariantResource::collection($productVariants->items()),
            'current_page' => $productVariants->currentPage(),
            'per_page' => $productVariants->perPage(),
            'total' => $productVariants->total(),
            'last_page' => $productVariants->lastPage(),
            'next_page_url' => $productVariants->nextPageUrl(),
            'prev_page_url' => $productVariants->previousPageUrl()
        ]);
    }

    /**
     * Crear variante de producto.
     */
    public function store(StoreProductVariantRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('productVariant', 'public');

            $validatedData['image'] = $path;
        }

        $productVariant = ProductVariant::create($validatedData);

        $productVariant->load('product');

        return new JsonResponse([
            'message' => 'Variante de producto creado exitosamente',
            'variant' => new ProductVariantResource($productVariant)
        ]);
    }

    /**
     * Mostrar variante de producto por id.
     */
    public function show($id): JsonResponse
    {
        $productVariant = ProductVariant::find($id);

        if(!$productVariant){
            return new JsonResponse(['message' => 'Variante de producto no encontrado'], 404);
        }

        $productVariant->load('product');

        return new JsonResponse(['productVariant' => new ProductVariantResource($productVariant)], 200);
    }

    /**
     * Actualizar variante de producto por id.
     */
    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        $productVariant = ProductVariant::find($id);

        if(!$productVariant){
            return new JsonResponse(['message' => 'Variante de producto no encontrado'], 404);
        }

        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            if ($productVariant->image && Storage::disk('public')->exists($productVariant->image)) {
                Storage::disk('public')->delete($productVariant->image);
            }

            // Guardar nueva imagen
            $path = $request->file('image')->store('product', 'public');
            $validatedData['image'] = $path;
        }

        $productVariant->update($validatedData);

        return new JsonResponse([
            'message' => 'Variante de producto actualizado con éxito', 
            'product' => new ProductVariantResource($productVariant)
        ],200);

    }

    /**
     * Eliminar variante de producto por id.
     */
    public function destroy($id)
    {
        $productVariant = ProductVariant::find($id);

        if(!$productVariant){
            return new JsonResponse(['message' => 'Variante de producto no encontrado'], 404);
        }

        $productVariant->delete();

        return new JsonResponse(['message' => 'Variante de producto eliminado correctamente'], 200);
    }
}
