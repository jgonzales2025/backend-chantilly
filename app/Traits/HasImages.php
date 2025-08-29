<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

trait HasImages
{
    /**
     * Relación polimórfica con imágenes
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }

    /**
     * Imagen principal
     */
    public function primaryImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('is_primary', true);
    }

    /**
     * Agregar imagen
     */
    public function addImage($path, $isPrimary = false, $sortOrder = 0)
    {
        return $this->images()->create([
            'path_url' => $path,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder
        ]);
    }

    /**
     * Eliminar todas las imágenes asociadas
     */
    public function deleteImages()
    {
        foreach ($this->images as $image) {
            if ($image->path_url && Storage::disk('public')->exists($image->path_url)) {
                Storage::disk('public')->delete($image->path_url);
            }
            $image->delete();
        }
    }

    /**
     * Establecer imagen como principal
     */
    public function setPrimaryImage($imageId): bool
    {
        $image = $this->images()->find($imageId);
        
        if (!$image) {
            return false;
        }

        // Quitar estado principal de todas las imágenes
        $this->images()->update(['is_primary' => false]);
        
        // Establecer nueva imagen como principal
        $image->update(['is_primary' => true]);
        
        return true;
    }
}
