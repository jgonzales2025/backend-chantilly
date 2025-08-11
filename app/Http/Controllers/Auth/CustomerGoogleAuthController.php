<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class CustomerGoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            
            $customer = Customer::where('email', $googleUser->getEmail())->first();
            
            if (!$customer) {
                // Puedes ajustar los campos según tu tabla
                $customer = Customer::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)), // Clave aleatoria
                ]);
            }
            // Crear token con Sanctum
            $token = $customer->createToken('auth_token')->plainTextToken;

            dd(config('app.frontend_url'));
            return redirect()->away(config('app.frontend_url') . '/auth/callback?token=' . $token);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error de autenticación con Google'], 500);
        }
    }
}
