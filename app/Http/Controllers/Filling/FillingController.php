<?php

namespace App\Http\Controllers\Filling;

use App\Http\Controllers\Controller;
use App\Models\Filling;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FillingController extends Controller
{
    /**
     * Mostrar todos los rellenos.
     */
    public function index(): JsonResponse
    {
        $fillings = Filling::all();

        return new JsonResponse($fillings, 200);
    }
}
