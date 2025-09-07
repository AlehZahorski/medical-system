<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginAction(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $patient = Patient::query()
            ->where('login', $request->login)
            ->first();

        if (
            !$patient
            || !Hash::check(
                $request->password,
                $patient->password
            )
        ) {
            return response()->json([
                'message' => 'Nieprawidłowe dane logowania',
            ], 401);
        }

        $tokenInstance = $patient->createToken('api_token');
        $tokenInstance->accessToken->update([
            'expires_at' => now()->addMinutes(config('sanctum.token_ttl', 60))
        ]);

        return response()->json([
            'token' => $tokenInstance->plainTextToken,
            'patient' => $patient
        ]);
    }

    public function logoutAction(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Wylogowano pomyślnie',
        ]);
    }
}
