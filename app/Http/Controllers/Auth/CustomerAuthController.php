<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->validated();

        $customer = Customer::where('email', $credentials['email'])->firstOrFail();

        if (!$customer || !Hash::check($credentials['password'], $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

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
