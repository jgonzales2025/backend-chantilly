<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Obtener todos los productos.
     */
    public function allProducts(Request $request): JsonResponse
    {
        try {
            $name = $request->query('name');
            $prodType = $request->query('product_type_id');

            $products = Product::when($name, function ($query) use ($name) {
            $query->where('short_description', 'LIKE', "%$name%");
            })
            ->when($prodType, function ($query) use ($prodType) {
            $query->where('product_type_id', $prodType);
            })
            ->get();

            $products->load('theme', 'category', 'productType', 'images');
            return new JsonResponse(ProductResource::collection($products), 200);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Error al obtener los productos', 'error' => $e->getMessage()], 500);
        }
    }


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
        $products->load('theme', 'category', 'productType', 'images');

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

        // Crear producto primero
        $product = Product::create($validatedData);

        $product->load('theme', 'category', 'productType', 'images');
        
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
            
        $product->load('theme', 'category', 'productType', 'images');
        return new JsonResponse(new ProductResource($product), 200);
    }

    /**
     * Actualizar producto por id.
     */
    public function addImages(UpdateProductRequest $request, $id): JsonResponse
    {
        $product = Product::with('images')->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }

        // Manejar imágenes si se envían
        if ($request->hasFile('images')) {
            // Verificar límite de 3 imágenes
            $currentImageCount = $product->images()->count();
            Log::info("Imágenes actuales del producto (ID: {$id}): {$currentImageCount}");
            if ($currentImageCount >= 3) {
                return new JsonResponse([
                    'message' => 'El producto ya tiene el máximo de 3 imágenes permitidas'
                ], 422);
            }
            // Verificar si el producto no tiene imágenes para establecer la primera como principal
            $isFirstImage = $currentImageCount === 0;

            $typeProductId = $product->product_type_id;
            $existingFolder = $this->pathDirectory($typeProductId);

            // Obtener el último sort_order para continuar la secuencia
            $lastSortOrder = $product->images()->max('sort_order') ?? -1;

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
                
                // La primera imagen será principal si el producto no tenía imágenes
                $isPrimary = $isFirstImage && $index === 0;

                $product->addImage(
                    $path, 
                    $isPrimary,
                    $lastSortOrder + $index + 1 // Continuar la secuencia
                );
            }
        }

        $product->load('theme', 'category', 'productType', 'images');
        
        return new JsonResponse([
            'message' => 'Imágenes del producto actualizadas con éxito',
            'product' => new ProductResource($product)
        ], 200);
    }

    /**
     * Eliminar producto
     */
    public function deleteImage(Request $request, $productId)
    {
        $request->validate([
            'image_index' => 'required|integer|min:0'
        ]);

        $product = Product::find($productId);
        
        if (!$product) {
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }
        
        $imageIndex = $request->input('image_index');
        $images = $product->images()->orderBy('sort_order')->get();

        if (!isset($images[$imageIndex])) {
            return new JsonResponse(['message' => 'Índice de imagen inválido'], 404);
        }

        $imageToDelete = $images[$imageIndex];
        
        // Verificar si la imagen a eliminar es la principal
        $wasPrimary = $imageToDelete->is_primary;
        
        // Eliminar archivo físico usando ImageService
        $this->imageService->deleteImage($imageToDelete->path_url);
        
        // Eliminar registro de la base de datos
        $imageToDelete->delete();
        
        // Si la imagen eliminada era la principal, establecer otra como principal
        if ($wasPrimary) {
            $remainingImages = $product->images()->orderBy('sort_order')->get();
            if ($remainingImages->isNotEmpty()) {
                $remainingImages->first()->update(['is_primary' => true]);
            }
        }

        $product->load('theme', 'category', 'productType', 'images');
        
        return new JsonResponse([
            'message' => 'Imagen eliminada con éxito',
            'product' => new ProductResource($product)
        ], 200);
    }

    public function indexAccesories(): JsonResponse
    {
        $accesorios = Product::where('product_type_id', 5)->get();

        if ($accesorios->isEmpty()) {
            return new JsonResponse(['message' => 'No hay accesorios registrados'], 404);
        }
        $accesorios->load('images');
        return new JsonResponse([
            'accesorios' => ProductResource::collection($accesorios)
        ]);
    }

    /**
     * Cambiar imagen principal del producto
     */
    public function setPrimaryImage(Request $request, $productId): JsonResponse
    {
        $request->validate([
            'image_index' => 'required|integer'
        ]);

        $product = Product::find($productId);
        
        if (!$product) {
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }
        
        $imageIndex = $request->input('image_index');
        $images = $product->images()->orderBy('sort_order')->get();

        if (!isset($images[$imageIndex])) {
            return new JsonResponse(['message' => 'Índice de imagen inválido'], 404);
        }

        $imageId = $images[$imageIndex]->id;

        if ($product->setPrimaryImage($imageId)) {
            $product->load('theme', 'category', 'productType', 'images');
            
            return new JsonResponse([
                'message' => 'Imagen principal actualizada con éxito',
                'product' => new ProductResource($product)
            ], 200);
        }

        return new JsonResponse(['message' => 'Imagen no encontrada para este producto'], 404);
    }

    private function pathDirectory($id)
    {
        match ($id) {
            1 => $folder = 'product/torta_vitrina',
            2 => $folder = 'product/torta_tematica',
            3 => $folder = 'product/postre',
            4 => $folder = 'product/bocadito',
            5 => $folder = 'product/accesorio',
            default => $folder = 'product/promociones',
        };
        return $folder;
    }
}
