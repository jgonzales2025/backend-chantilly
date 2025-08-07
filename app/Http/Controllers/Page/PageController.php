<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Mostrar Páginas.
     */
    public function index(): JsonResponse
    {
        $pages = Page::all();

        if ($pages->isEmpty()){
            return new JsonResponse(['message' => 'Paginas no registradas']);
        }

        return new JsonResponse($pages, 200);
    }
}
