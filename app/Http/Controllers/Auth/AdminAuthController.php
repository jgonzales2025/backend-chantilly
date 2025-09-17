<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = User::where('username', $request->username)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        $cookie = cookie(
            'auth_admin_token',
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
            'user' => $admin
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        // Eliminar la cookie de autenticación
        $cookie = cookie()->forget('auth_admin_token');

        return response()->json([
            'message' => 'Cierre de sesión exitoso'
        ], 200)->withCookie($cookie);
    }
}
