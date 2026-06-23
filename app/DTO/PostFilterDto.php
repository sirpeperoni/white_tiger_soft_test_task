<?php

namespace App\DTO;

class PostFilterDto
{
    public function __construct(
        public readonly string $sortBy = 'date',
        public readonly string $sortOrder = 'desc',
        public readonly int $limit = 10,
        public readonly int $offset = 0,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
    ) {}
}
