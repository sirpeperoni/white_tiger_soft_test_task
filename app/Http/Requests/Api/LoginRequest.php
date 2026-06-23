<?php

namespace App\Http\Requests\Api;

use App\DTO\LoginDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function toDto(): LoginDto
    {
        return new LoginDto(
            email: $this->validated('email'),
            password: $this->validated('password'),
        );
    }
}
