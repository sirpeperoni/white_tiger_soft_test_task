<?php

namespace App\Http\Requests\Api;

use App\DTO\CreatePostDto;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'text'  => ['required', 'string'],
        ];
    }

    public function toDto(int $userId): CreatePostDto
    {
        return new CreatePostDto(
            userId: $userId,
            title: $this->validated('title'),
            text: $this->validated('text'),
        );
    }
}
