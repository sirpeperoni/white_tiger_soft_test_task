<?php

namespace App\Http\Requests\Api;

use App\DTO\PostFilterDto;
use Illuminate\Foundation\Http\FormRequest;

class PostsFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sort_by'    => ['sometimes', 'string', 'in:date,title'],
            'sort_order' => ['sometimes', 'string', 'in:asc,desc'],
            'limit'      => ['sometimes', 'integer', 'min:1', 'max:100'],
            'offset'     => ['sometimes', 'integer', 'min:0'],
            'date_from'  => ['sometimes', 'date'],
            'date_to'    => ['sometimes', 'date', 'after_or_equal:date_from'],
        ];
    }

    public function toDto(): PostFilterDto
    {
        return new PostFilterDto(
            sortBy: $this->validated('sort_by', 'date'),
            sortOrder: $this->validated('sort_order', 'desc'),
            limit: (int) $this->validated('limit', 10),
            offset: (int) $this->validated('offset', 0),
            dateFrom: $this->validated('date_from'),
            dateTo: $this->validated('date_to'),
        );
    }
}
