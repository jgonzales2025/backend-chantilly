<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::guard('customer')->attempt($credentials)){
            return new JsonResponse(['message' => 'Las credenciales proporcionadas son incorrectas.']);
        }

        $customer = Customer::where('email', $credentials['email'])->firstOrFail();
        $token = $customer->createToken('customer_token')->plainTextToken;

        return new JsonResponse([
            'message' => 'Login exitoso',
            'token' => $token,
            'token_type' => 'Bearer',
            'customer' => $customer
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return new JsonResponse(['message' => 'Logout exitoso']);
    }
}
