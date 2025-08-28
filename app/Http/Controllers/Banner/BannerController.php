<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $title = $request->query('title');
        $banners = Banner::when($title, fn($query) => $query->where('title', 'like', "%{$title}%"))
            ->get();
        return new JsonResponse(BannerResource::collection($banners), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $validatedData['image_path'] = $this->processImage(
                $request->file('image'), 
                $validatedData['title']
            );
        }

        if ($request->hasFile('image_movil')) {
            $validatedData['image_path_movil'] = $this->processImage(
                $request->file('image_movil'), 
                $validatedData['title'] . '_movil'
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
     * Update the specified resource in storage.
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

            $validatedData['image_path'] = $this->processImage(
                $request->file('image'), 
                $validatedData['title']
            );
        }

        // Procesar imagen móvil
        if ($request->hasFile('image_movil')) {
            // Eliminar imagen móvil anterior si existe
            if ($banner->image_path_movil && Storage::disk('public')->exists($banner->image_path_movil)) {
                Storage::disk('public')->delete($banner->image_path_movil);
            }

            $validatedData['image_path_movil'] = $this->processImage(
                $request->file('image_movil'), 
                $validatedData['title'] . '_movil'
            );
        }

        $banner->update($validatedData);

        return new JsonResponse(new BannerResource($banner), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return new JsonResponse(['message' => 'Banner no encontrado'], 404);
        }

        $banner->delete();

        return new JsonResponse(['message' => 'Banner eliminado con éxito'], 200);
    }

    private function processImage($image, $name): string
    {
        $imageName = $name . '.jpg';
        
        $manager = new ImageManager(new Driver());
        $convertedImage = $manager->read($image->getPathname())->toJpeg(85);
        
        Storage::disk('public')->put('banners/' . $imageName, $convertedImage);
        
        return 'banners/' . $imageName;
    }
}
