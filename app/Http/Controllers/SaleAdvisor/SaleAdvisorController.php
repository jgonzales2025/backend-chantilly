<?php

namespace App\Http\Controllers\SaleAdvisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleAdvisor\StoreSaleAdvisorRequest;
use App\Http\Requests\SaleAdvisor\UpdateSaleAdvisorRequest;
use App\Models\SaleAdvisor;
use Illuminate\Http\JsonResponse;

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

    /**
     * Mostrar asesor de venta
     */
    public function show($id): JsonResponse
    {
        $saleAdvisor = SaleAdvisor::find($id);

        if (!$saleAdvisor)
        {
            return new JsonResponse(['message' => 'Sale advisor not found'], 404);
        }

        return new JsonResponse($saleAdvisor, 200);
    }

    /**
     * Actualizar asesor de venta
     */
    public function update(UpdateSaleAdvisorRequest $request, $id): JsonResponse
    {
        $saleAdvisor = SaleAdvisor::find($id);

        if (!$saleAdvisor) {
            return new JsonResponse(['message' => 'Sale advisor not found'], 404);
        }

        $saleAdvisor->update($request->validated());

        return new JsonResponse(['message' => 'Sale advisor updated successfully', $saleAdvisor], 200);
    }
}
