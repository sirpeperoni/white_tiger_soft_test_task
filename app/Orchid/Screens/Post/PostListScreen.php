<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Post;

use App\Orchid\Layouts\Post\PostListLayout;
use App\Services\PostService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PostListScreen extends Screen
{
    public function __construct(private PostService $postService) {}

    public function query(): iterable
    {
        return [
            'posts' => $this->postService->getAdminList(),
        ];
    }

    public function name(): ?string
    {
        return 'Публикации';
    }

    public function description(): ?string
    {
        return 'Список всех публикаций в блоге.';
    }

    public function permission(): ?iterable
    {
        return ['platform.posts'];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить')
                ->icon('bs.plus-circle')
                ->route('platform.posts.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PostListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        $this->postService->removeById((int) $request->input('id'));

        Toast::info('Публикация удалена.');
    }
}
