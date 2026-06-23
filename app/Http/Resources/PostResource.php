<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'text'       => $this->text,
            'created_at' => $this->created_at->toDateTimeString(),
            'author'     => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
        ];
    }
}
