<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->login($request->toDto());

            return response()->json(['accessToken' => $token]);
        } catch (AuthException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->authService->register($request->toDto());

        return response()->json(['accessToken' => $token], 201);
    }
}
