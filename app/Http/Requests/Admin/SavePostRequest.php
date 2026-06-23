<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SavePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'post.title'   => ['required', 'string', 'max:255'],
            'post.text'    => ['required', 'string'],
            'post.user_id' => ['required', 'exists:users,id'],
        ];
    }
}
