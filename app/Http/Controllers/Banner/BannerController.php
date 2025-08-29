<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BannerController extends Controller
{
    /**
     * Listar banners.
     */
    public function index(Request $request): JsonResponse
    {
        $title = $request->query('title');
        $banners = Banner::when($title, fn($query) => $query->where('title', 'like', "%{$title}%"))
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
            $validatedData['image_path'] = $this->processImage(
                $request->file('image')
            );
        }

        $banner = Banner::create($validatedData);

        return new JsonResponse(new BannerResource($banner), 201);
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
        Log::info("Datos", $validatedData);
        if ($request->hasFile('image')) {
            Log::info("Actualizando imagen");
            // Eliminar imagen anterior si existe
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $validatedData['image_path'] = $this->processImage(
                $request->file('image'), 
                $validatedData['title'] ?? null
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

    private function processImage($image): string
    {
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $imageName = $originalName . '.jpg';

        $manager = new ImageManager(new Driver());
        $convertedImage = $manager->read($image->getPathname())->toJpeg(85);
        
        Storage::disk('public')->put('banners/' . $imageName, $convertedImage);
        
        return 'banners/' . $imageName;
    }
}
