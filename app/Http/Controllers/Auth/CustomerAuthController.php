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
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $customer = Customer::where('email', $credentials['email'])->first();

        if (!$customer || !Hash::check($credentials['password'], $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $customer->createToken('customer_token')->plainTextToken;

        // Configurar la cookie con el token
        $cookie = cookie(
            'auth_token',
            $token,
            60 * 24 * 7, // 7 días por defecto
            null,
            null,
            config('app.env') === 'production', // Solo HTTPS en producción
            true, // HttpOnly
            false,
            'Lax' // Protección CSRF
        );

        return response()->json([
            'message' => 'Login exitoso',
            'customer' => $customer
        ], 201)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        // Eliminar la cookie de autenticación
        $cookie = cookie()->forget('auth_token');

        return response()->json(['message' => 'Logout exitoso'])->withCookie($cookie);
    }
}
