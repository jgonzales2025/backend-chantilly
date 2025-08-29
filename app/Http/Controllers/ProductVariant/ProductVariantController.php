<?php

namespace App\Http\Controllers\ProductVariant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\ProductVariant\StoreProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductVariantController extends Controller
{

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Obtener todas las variantes de productos.
     */
    public function allProductVariants(Request $request): JsonResponse
    {
        $name = $request->query('name');
        $prodType = $request->query('product_type_id');

        $productVariants = ProductVariant::with('product', 'product.category', 'product.productType', 'images')
        ->when($name, function ($query) use ($name) {
            $query->where('description', 'LIKE', "%$name%");
        })
        ->when($prodType, function ($query) use ($prodType) {
            $query->whereHas('products', function ($queryProduct) use ($prodType){
                $queryProduct->where('product_type_id', $prodType);
            });
        })
        ->get();

        return new JsonResponse(ProductVariantResource::collection($productVariants), 200);
    }

    /**
     * Listar variantes de productos.
     */
    public function index(Request $request): JsonResponse
    {
        $name = $request->query('name');

        $productVariants = ProductVariant::with('product', 'product.category', 'product.productType', 'images')
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
        //$portionName = $request->query('portion_name');

        $productVariant = ProductVariant::with('product', 'images')->find($id);

        if(!$productVariant){
            return new JsonResponse(['message' => 'Variante de producto no encontrado'], 404);
        }

        return new JsonResponse(['productVariant' => new ProductVariantResource($productVariant)], 200);
    }


    /**
     * Mostrar variante de producto por porción.
     */
    public function showByPortion($id, Request $request): JsonResponse
    {
        $portionName = $request->query('portion_name');

        $productVariant = ProductVariant::with('images')
            ->where('product_id', $id)
            ->when($portionName, function($query) use ($portionName) {
                $query->where('portions', 'LIKE', "%$portionName%");
            })
            ->get();

        if ($productVariant->isEmpty()) {
            return new JsonResponse(['message' => 'No hay variantes de producto para esta porción'], 404);
        }

        return new JsonResponse([
            'data' => ProductVariantResource::collection($productVariant)
        ]);
    }

    /**
     * Actualizar variante de producto por id.
     */
    public function update(UpdateProductVariantRequest $request, $id): JsonResponse
    {
        $productVariant = ProductVariant::find($id);

        if(!$productVariant){
            return new JsonResponse(['message' => 'Variante de producto no encontrado'], 404);
        }

        $validatedData = $request->validated();

        // Manejar imágenes si se envían
        if ($request->hasFile('images')) {
            // Obtener la carpeta de las imágenes existentes (si las hay)
            $existingFolder = 'productVariant'; // Carpeta por defecto
            
            if ($productVariant->images->isNotEmpty()) {
                $firstImagePath = $productVariant->images->first()->path_url;
                // Extraer la carpeta del path
                $existingFolder = dirname($firstImagePath);
            }

            Log::info('Usando carpeta existente para ProductVariant:', ['folder' => $existingFolder]);

            // Eliminar imágenes existentes
            $productVariant->deleteImages();

            // Obtener imágenes de diferentes formas (soporte para Postman)
            $imageFiles = [];
            
            if (is_array($request->file('images'))) {
                $imageFiles = $request->file('images');
            } else {
                // Si Postman envía como images.0, images.1, etc.
                foreach ($request->allFiles() as $key => $file) {
                    if (preg_match('/^images\.\d+$/', $key)) {
                        $imageFiles[] = $file;
                    }
                }
            }

            // Agregar nuevas imágenes en la misma carpeta que antes
            foreach ($imageFiles as $index => $imageFile) {
                $path = $this->imageService->uploadImage($imageFile, $existingFolder);
                
                $productVariant->addImage(
                    $path, 
                    $index === 0, // Primera imagen como principal
                    $index
                );
            }
        }

        $productVariant->update($validatedData);
        $productVariant->load('images');

        return new JsonResponse([
            'message' => 'Variante de producto actualizado con éxito', 
            'product' => new ProductVariantResource($productVariant)
        ], 200);

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

        // Eliminar imágenes antes de eliminar la variante
        $productVariant->deleteImages();
        
        $productVariant->delete();

        return new JsonResponse(['message' => 'Variante de producto eliminado correctamente'], 200);
    }
}
