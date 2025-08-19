<?php

namespace App\Http\Controllers\SaleAdvisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleAdvisor\StoreSaleAdvisorRequest;
use App\Models\SaleAdvisor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleAdvisorController extends Controller
{
    /**
     * Listar los asesores de ventas
     */
    public function index(): JsonResponse
    {
        $saleAdvisors = SaleAdvisor::all();

        if ($saleAdvisors->isEmpty()) {
            return response()->json(['message' => 'No sale advisors found'], 404);
        }

        return new JsonResponse($saleAdvisors, 200);
    }

    /**
     * Crear asesor de venta
     */
    public function store(StoreSaleAdvisorRequest $request): JsonResponse
    {
        $saleAdvisor = SaleAdvisor::create($request->validated());

        return new JsonResponse([
            'message' => 'Sale advisor created successfully',
            'data' => $saleAdvisor
        ], 201);
    }
}
