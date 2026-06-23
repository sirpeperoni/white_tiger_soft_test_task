<?php

namespace App\DTO;

class CreatePostDto
{
    public function __construct(
        public readonly int $userId,
        public readonly string $title,
        public readonly string $text,
    ) {}
}
