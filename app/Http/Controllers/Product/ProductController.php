<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Mostrar productos.
     */
    public function index(Request $request)
    {
        $themeId = $request->query('theme_id');
        $name = $request->query('name');
        $prodType = $request->query('product_type_id');
        $bestStatus = $request->query('best_status');

        $products = Product::when($themeId, function ($query) use ($themeId){
            $query->where('theme_id', $themeId);
        })
        ->when($name, function ($query) use ($name){
            $query->where('short_description', 'LIKE', "%$name%");
        })
        ->when($prodType, function ($query) use ($prodType){
            $query->where('product_type_id', $prodType);
        })
        ->when($bestStatus, function ($query) use ($bestStatus){
            $query->where('best_status', $bestStatus);
        })
        ->paginate(8);
        
        if($products->isEmpty()){
            return new JsonResponse(['message' => 'No hay productos registrados']);
        }
        $products->load('theme', 'category', 'productType');

        return new JsonResponse([
            'data' => ProductResource::collection($products->items()),
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

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product', 'public');

            $validatedData['image'] = $path;
        }

        $product = Product::create($validatedData);

        $product->load('theme', 'category', 'productType');
        
        return new JsonResponse([
            'message' => 'Producto registrado con éxito',
            'product' => new ProductResource($product)
        ], 201);
    }

    /**
     * Mostrar producto por id
     */
    public function show($id): JsonResponse
    {
        $product = Product::find($id);

        if(!$product){
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }
        $product->load('theme', 'category', 'productType');
        return new JsonResponse(new ProductResource($product), 200);
    }

    /**
     * Actualizar producto por id.
     */
    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        $product = Product::find($id);

        if(!$product){
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }

        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Guardar nueva imagen
            $path = $request->file('image')->store('product', 'public');
            $validatedData['image'] = $path;
        }

        $product->update($validatedData);

        return new JsonResponse(['message' => 'Producto actualizado con éxito','product' => new ProductResource($product)], 200);
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if(!$product){
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }

        $product->delete();

        return new JsonResponse(['message' => 'Producto eliminado con éxito'], 200);
    }
}
