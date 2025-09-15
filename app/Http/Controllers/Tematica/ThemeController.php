<?php

namespace App\Http\Controllers\Tematica;

use App\Http\Controllers\Controller;
use App\Http\Requests\Theme\StoreThemeRequest;
use App\Http\Requests\Theme\UpdateThemeRequest;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;

class ThemeController extends Controller
{

    /**
     * Listar temáticas.
     */
    public function index(): JsonResponse
    {
        $themes = Theme::all();

        if ($themes->isEmpty()){
            return new JsonResponse(['message' => 'No hay temáticas registradas']);
        }

        return new JsonResponse($themes);
    }

    public function store(StoreThemeRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $theme = Theme::create($validatedData);

        return new JsonResponse([
            'message' => 'Temática creada exitosamente',
            'theme' => $theme
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return new JsonResponse(['message' => 'Temática no encontrada'], 404);
        }

        return new JsonResponse($theme);
    }

    public function update(UpdateThemeRequest $request, $id): JsonResponse
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return new JsonResponse(['message' => 'Temática no encontrada'], 404);
        }

        $validatedData = $request->validated();

        $theme->update($validatedData);

        return new JsonResponse([
            'message' => 'Temática actualizada exitosamente',
            'theme' => $theme
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return new JsonResponse(['message' => 'Temática no encontrada'], 404);
        }

        $theme->delete();

        return new JsonResponse(['message' => 'Temática eliminada exitosamente']);
    }
}
