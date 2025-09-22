<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BulkStoreRequest;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Listar banners.
     */
    public function index(Request $request): JsonResponse
    {
        $title = $request->query('title');
        $banners = Banner::when($title, fn($query) => $query->where('title', 'like', "%{$title}%"))
            ->orderBy('display_order', 'asc')
            ->get();
        
        return new JsonResponse(BannerResource::collection($banners), 200);
    }

    /**
     * Crear banner.
     */
    public function store(StoreBannerRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $maxDisplayOrder = Banner::max('display_order') ?? 0;
        $validatedData['display_order'] = $maxDisplayOrder + 1;

        if ($request->hasFile('image')) {
            $validatedData['image_path'] = $this->imageService->uploadImage(
                $request->file('image'),
                'banners'
            );
        }

        $banner = Banner::create($validatedData);

        return new JsonResponse(new BannerResource($banner), 201);
    }

    /**
     * Almacenar múltiples banners.
     */
    public function bulkStore(BulkStoreRequest $request): JsonResponse
    {

        $validatedData = $request->validated();

        $cantidad = Banner::count();

        if($cantidad == 12) {
            return new JsonResponse(['message' => 'Se ha alcanzado el límite máximo de banners'], 429);
        }

        $banners = [];
        foreach ($validatedData['banners'] as $data) {
            $maxDisplayOrder = Banner::max('display_order') ?? 0;
            $data['display_order'] = $maxDisplayOrder + 1;

            if (isset($data['image'])) {
                $data['image_path'] = $this->imageService->uploadImage($data['image'], 'banners');
            }

            $banners[] = Banner::create($data);
        }

        return new JsonResponse(BannerResource::collection($banners), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Actualizar banner.
     */
    public function update(UpdateBannerRequest $request, $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return new JsonResponse(['message' => 'Banner no encontrado'], 404);
        }

        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $validatedData['image_path'] = $this->imageService->uploadImage(
                $request->file('image'), 
                'banners'
            );
        }

        $banner->update($validatedData);
        return new JsonResponse(new BannerResource($banner), 200);
    }

    /**
     * Eliminar banner.
     */
    public function destroy($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return new JsonResponse(['message' => 'Banner no encontrado'], 404);
        }
        // Eliminar imagen si existe
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return new JsonResponse(['message' => 'Banner eliminado con éxito'], 200);
    }

    /**
     * Eliminar todos los banners.
     */
    public function destroyAll(Request $request): JsonResponse
    {
        $banners = Banner::all();
        
        if ($banners->isEmpty()) {
            return new JsonResponse(['message' => 'No hay banners para eliminar'], 200);
        }

        // Eliminar todas las imágenes asociadas
        foreach ($banners as $banner) {
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
        }

        // Eliminar todos los banners de la base de datos
        $deletedCount = Banner::count();
        Banner::truncate();

        return new JsonResponse([
            'message' => 'Todos los banners han sido eliminados con éxito',
            'deleted_count' => $deletedCount
        ], 200);
    }
}
