<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class CustomerForgotPasswordController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Enviar enlace de restablecimiento de contraseña.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Correo de restablecimiento enviado.'], 200)
            : response()->json(['message' => 'No se pudo enviar el correo.'], 400);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($customer, $password) {
                $customer->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Contraseña restablecida correctamente.'], 200)
            : response()->json(['message' => 'Token inválido o expirado.'], 400);
    }

    public function sendRecoveryCode(Request $request)
    {
        $request->validate(['phone' => 'required']);

        $customer = Customer::where('phone', $request->phone)->first();
        if (!$customer) {
            return response()->json(['message' => 'Teléfono no encontrado.'], 404);
        }

        $code = rand(100000, 999999);
        $customer->recovery_code = $code;
        $customer->recovery_code_expires_at = now()->addMinutes(10);
        $customer->save();

        // Formatear número para Perú
        $phone = "+51" . ltrim($customer->phone, '0'); // sin el +51, ya que la API espera el número local
        // Enviar SMS usando la API de HARD SYSTEM PERU
        $sendMsg = $this->smsService->send($phone, $code);

         // Verificar si el SMS fue enviado correctamente
        if ($sendMsg) {
            return response()->json(['message' => 'Código enviado por SMS.'], 200);
        }

        return response()->json([
            'message' => 'Error al enviar el SMS.'
        ], 500);

    }

    public function verifyRecoveryCode(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'code' => 'required',
        ]);

        $customer = Customer::where('phone', $request->phone)->first();

        if (
            !$customer ||
            $customer->recovery_code !== $request->code ||
            now()->gt($customer->recovery_code_expires_at)
        ) {
            return response()->json(['message' => 'Código inválido o expirado.'], 400);
        }

        return response()->json(['message' => 'Código válido.'], 200);
    }

    public function resetWithCode(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'code' => 'required',
            'password' => 'required|confirmed',
        ]);

        $customer = Customer::where('phone', $request->phone)->first();

        if (
            !$customer ||
            $customer->recovery_code !== $request->code ||
            now()->gt($customer->recovery_code_expires_at)
        ) {
            return response()->json(['message' => 'Código inválido o expirado.'], 400);
        }

        $customer->password = Hash::make($request->password);
        $customer->recovery_code = null;
        $customer->recovery_code_expires_at = null;
        $customer->save();

        return response()->json(['message' => 'Contraseña restablecida correctamente.'], 200);
    }
}
