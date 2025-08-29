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
        $product->load('theme', 'category', 'productType', 'images');
        return new JsonResponse(new ProductResource($product), 200);
    }

    /**
     * Actualizar producto por id.
     */
    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }

        // Manejar imágenes si se envían
        if ($request->hasFile('images')) {
            // Obtener la carpeta de las imágenes existentes (si las hay)
            $existingFolder = 'product'; // Carpeta por defecto
            
            if ($product->images->isNotEmpty()) {
                $firstImagePath = $product->images->first()->path_url;
                // Extraer la carpeta del path: "product/bocadito/BOC01.jpg" -> "product/bocadito"
                $existingFolder = dirname($firstImagePath);
            }

            Log::info('Usando carpeta existente:', ['folder' => $existingFolder]);

            // Eliminar imágenes existentes
            $product->deleteImages();

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
                
                $product->addImage(
                    $path, 
                    $index === 0, // Primera imagen como principal
                    $index
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
    public function destroy($id)
    {
        $product = Product::find($id);

        if(!$product){
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }

        $product->deleteImages();

        $product->delete();

        return new JsonResponse(['message' => 'Producto eliminado con éxito'], 200);
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
            'image_id' => 'required|integer|exists:images,id'
        ]);

        $product = Product::find($productId);
        
        if (!$product) {
            return new JsonResponse(['message' => 'Producto no encontrado'], 404);
        }

        $imageId = $request->input('image_id');
        
        if ($product->setPrimaryImage($imageId)) {
            $product->load('theme', 'category', 'productType', 'images');
            
            return new JsonResponse([
                'message' => 'Imagen principal actualizada con éxito',
                'product' => new ProductResource($product)
            ], 200);
        }

        return new JsonResponse(['message' => 'Imagen no encontrada para este producto'], 404);
    }
}
