<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\StoreBannerRequest;
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
    public function index()
    {
        $banners = Banner::all();
        return new JsonResponse(BannerResource::collection($banners), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Generar nombre único para la imagen
            $imageName = $validatedData['title'] . '.jpg';
            
            // Crear instancia del ImageManager para v3
            $manager = new ImageManager(new Driver());
            
            // Convertir imagen a JPG usando Intervention Image v3
            $convertedImage = $manager->read($image->getPathname())
                ->toJpeg(85); // 85% calidad
            
            // Guardar la imagen convertida
            Storage::disk('public')->put('banners/' . $imageName, $convertedImage);
            
            // Agregar el nombre de archivo a los datos validados
            $validatedData['image_path'] = 'banners/' . $imageName;
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
