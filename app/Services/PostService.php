<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CreatePostDto;
use App\DTO\PostFilterDto;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PostService
{
    public function getAdminList(): LengthAwarePaginator
    {
        return Post::with('user')->orderBy('created_at', 'desc')->paginate();
    }

    public function save(Post $post, array $data): void
    {
        $post->fill($data)->save();
    }

    public function removeById(int $id): void
    {
        Post::destroy($id);
    }

    public function remove(Post $post): void
    {
        $this->removeById($post->getKey());
    }

    public function create(CreatePostDto $dto): Post
    {
        return Post::create([
            'user_id' => $dto->userId,
            'title'   => $dto->title,
            'text'    => $dto->text,
        ]);
    }

    public function getAll(PostFilterDto $dto): array
    {
        $query = Post::query()->with('user');

        $this->applyFilters($query, $dto);

        return $this->paginate($query, $dto);
    }

    public function getMy(int $userId, PostFilterDto $dto): array
    {
        $query = Post::query()->with('user')->where('user_id', '=', $userId);

        $this->applyFilters($query, $dto);

        return $this->paginate($query, $dto);
    }

    private function applyFilters(Builder $query, PostFilterDto $dto): void
    {
        $sortColumn = $dto->sortBy === 'title' ? 'title' : 'created_at';
        $query->orderBy($sortColumn, $dto->sortOrder);

        if ($dto->dateFrom !== null) {
            $query->whereDate('created_at', '>=', $dto->dateFrom);
        }

        if ($dto->dateTo !== null) {
            $query->whereDate('created_at', '<=', $dto->dateTo);
        }
    }

    private function paginate(Builder $query, PostFilterDto $dto): array
    {
        $total = $query->count();
        $posts = $query->limit($dto->limit)->offset($dto->offset)->get();

        return [
            'posts'  => $posts,
            'total'  => $total,
            'limit'  => $dto->limit,
            'offset' => $dto->offset,
        ];
    }
}
