<?php

namespace App\Http\Controllers\Tematica;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{

    /**
     * Listar temáticas.
     */
    public function index(): JsonResponse
    {
        $themes = Theme::all();

        if ($themes->isEmpty()){
            return new JsonResponse(['message' => 'No hay tematicas registradas']);
        }

        return new JsonResponse($themes);
    }
}
