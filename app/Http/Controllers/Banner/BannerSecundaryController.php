<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerSecundary\StoreBannerSecundaryRequest;
use App\Http\Requests\BannerSecundary\UpdateBannerSecundaryRequest;
use App\Http\Resources\BannerSecundaryResource;
use App\Models\BannerSecundary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BannerSecundaryController extends Controller
{
    /**
     * Listar banner secundario.
     */
    public function index(Request $request): JsonResponse
    {
        $title = $request->query('title');
        $banners = BannerSecundary::when($title, fn($query) => $query->where('title', 'like', "%{$title}%"))
            ->get();
        return new JsonResponse(BannerSecundaryResource::collection($banners), 200);
    }

    /**
     * Crear un nuevo banner secundario
     */
    public function store(StoreBannerSecundaryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $validatedData['image_path'] = $this->processImage(
                $request->file('image')
            );
        }

        if ($request->hasFile('image_movil')) {
            $validatedData['image_path_movil'] = $this->processImage(
                $request->file('image_movil')
            );
        }

        $bannerSecundary = BannerSecundary::create($validatedData);

        return new JsonResponse(['message' => 'Banner creado con éxito', 'banner' => $bannerSecundary], 201);
    }

    /**
     * Actualizar banner secundario.
     */
    public function update(UpdateBannerSecundaryRequest $request, $id)
    {
        $bannerSecundary = BannerSecundary::find($id);

        if (!$bannerSecundary) {
            return new JsonResponse(['message' => 'Banner no encontrado'], 404);
        }

        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($bannerSecundary->image_path && Storage::disk('public')->exists($bannerSecundary->image_path)) {
                Storage::disk('public')->delete($bannerSecundary->image_path);
            }

            $validatedData['image_path'] = $this->processImage(
                $request->file('image'),
            );
        }

        // Procesar imagen móvil
        if ($request->hasFile('image_movil')) {
            // Eliminar imagen móvil anterior si existe
            if ($bannerSecundary->image_path_movil && Storage::disk('public')->exists($bannerSecundary->image_path_movil)) {
                Storage::disk('public')->delete($bannerSecundary->image_path_movil);
            }

            $validatedData['image_path_movil'] = $this->processImage(
                $request->file('image_movil')
            );
        }

        $bannerSecundary->update($validatedData);

        return new JsonResponse(new BannerSecundaryResource($bannerSecundary), 200);
    }

    /**
     * Eliminar banner secundario.
     */
    public function destroy($id): JsonResponse
    {
        $bannerSecundary = BannerSecundary::find($id);

        if (!$bannerSecundary) {
            return new JsonResponse(['message' => 'Banner no encontrado'], 404);
        }

        // Eliminar imagen si existe
        if ($bannerSecundary->image_path && Storage::disk('public')->exists($bannerSecundary->image_path)) {
            Storage::disk('public')->delete($bannerSecundary->image_path);
        }

        // Eliminar imagen móvil si existe
        if ($bannerSecundary->image_path_movil && Storage::disk('public')->exists($bannerSecundary->image_path_movil)) {
            Storage::disk('public')->delete($bannerSecundary->image_path_movil);
        }

        $bannerSecundary->delete();

        return new JsonResponse(['message' => 'Banner eliminado con éxito'], 200);
    }

    private function processImage($image): string
    {
        $imageName = $image->getClientOriginalName();
        Log::info("Nombre original de la imagen: {$imageName}");
        $manager = new ImageManager(new Driver());
        $convertedImage = $manager->read($image->getPathname())->toJpeg(85);
        
        Storage::disk('public')->put('banners/' . $imageName, $convertedImage);
        
        return 'banners/' . $imageName;
    }
}
