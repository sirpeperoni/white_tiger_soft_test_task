<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\LoginDto;
use App\DTO\RegisterDto;
use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private const DUMMY_HASH = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    public function login(LoginDto $dto): string
    {
        $user = User::firstWhere('email', $dto->email);

        $passwordValid = Hash::check($dto->password, $user?->password ?? self::DUMMY_HASH);

        if (!$user || !$passwordValid) {
            throw new AuthException('Неверный email или пароль.');
        }

        return $user->createToken('auth_token', ['*'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;
    }

    public function register(RegisterDto $dto): string
    {
        $user = User::create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => Hash::make($dto->password),
        ]);

        return $user->createToken('auth_token', ['*'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;
    }
}
