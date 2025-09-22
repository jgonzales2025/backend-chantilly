<?php

namespace App\Http\Controllers\OrderStatus;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatus\StoreOrderStatusRequest;
use App\Http\Requests\OrderStatus\UpdateOrderStatusRequest;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = OrderStatus::all();
        return response()->json($statuses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderStatusRequest $request)
    {
        $validatedData = $request->validated();

        $nextOrder = OrderStatus::max('order') + 1;
        $validatedData['order'] = $nextOrder;
        $validatedData['order_backup'] = $validatedData['order'];

        $status = OrderStatus::create($validatedData);

        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $status = OrderStatus::find($id);

        if (!$status) {
            return response()->json(['message' => 'Estado no encontrado'], 404);
        }

        return response()->json($status);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusRequest $request, $id)
    {
        $status = OrderStatus::find($id);

        if (!$status) {
            return response()->json(['message' => 'Estado no encontrado'], 404);
        }

        $validatedData = $request->validated();

        if ($validatedData['status'] === 'Inactivo') {
            $status->order_backup = $status->order;
            $validatedData['order'] = null;
            $status->update($validatedData);

            $activeStatuses = OrderStatus::where('status', 'Activo')->orderBy('order')->get();
            foreach ($activeStatuses as $index => $activeStatus) {
                $activeStatus->order = $index + 1;
                $activeStatus->save();
            }
            Log::info($activeStatuses);
        } else {
            $status->order = $status->order_backup;
            $status->update($validatedData);
            
            $activeStatuses = OrderStatus::where('status', 'Activo')->orderBy('order')->get();
            foreach ($activeStatuses as $index => $activeStatus) {
                $activeStatus->order = $index + 1;
                $activeStatus->save();
            }
        }

        return response()->json($status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $status = OrderStatus::find($id);

        if (!$status) {
            return response()->json(['message' => 'Estado no encontrado'], 404);
        }

        $status->delete();

        return response()->json(['message' => 'Estado eliminado con éxito']);
    }
}
