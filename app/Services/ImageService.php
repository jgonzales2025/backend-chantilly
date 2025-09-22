<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Subir y procesar imagen
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images'): string
    {
        // Obtener nombre sin extensión
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // Siempre convertir a AVIF
        $imageName = $originalName . '.avif';
        $convertedImage = $this->manager->read($file->getPathname())
            ->toAvif(85);
        
        $path = $folder . '/' . $imageName;
        Storage::disk('public')->put($path, $convertedImage);
        
        return $path;
    }

    /**
     * Eliminar imagen
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Generar múltiples tamaños
     */
    /* public function createVariants(UploadedFile $file, string $folder): array
    {
        $variants = [];
        $sizes = [
            'thumbnail' => [150, 150],
            'medium' => [400, 400], 
            'large' => [800, 800]
        ];

        foreach ($sizes as $variant => $dimensions) {
            $imageName = time() . '_' . uniqid() . "_{$variant}.jpg";
            
            $resizedImage = $this->manager->read($file->getPathname())
                ->resize($dimensions[0], $dimensions[1], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->toJpeg(85);
            
            $path = $folder . '/' . $imageName;
            Storage::disk('public')->put($path, $resizedImage);
            
            $variants[$variant] = $path;
        }

        return $variants;
    } */
}