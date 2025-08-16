<?php

namespace App\Http\Controllers\MessageCustomerBot;

use App\Http\Controllers\Controller;
use App\Models\MessageCustomerBot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageCustomerBotController extends Controller
{
    public function index(): JsonResponse
    {
        $messages = MessageCustomerBot::all();

        if ($messages->isEmpty()) {
            return new JsonResponse(['message' => 'No hay mensajes registrados'], 404);
        }

        return new JsonResponse($messages, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'session_id' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $message = MessageCustomerBot::create($validatedData);

        return new JsonResponse([
            'message' => 'Mensaje registrado con éxito',
            'data' => $message
        ], 201);
    }
}
