<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Post;

use App\Http\Requests\Admin\SavePostRequest;
use App\Models\Post;
use App\Orchid\Layouts\Post\PostEditLayout;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PostEditScreen extends Screen
{
    public ?Post $post = null;

    public function __construct(private PostService $postService) {}

    public function query(Post $post): iterable
    {
        return [
            'post' => $post,
        ];
    }

    public function name(): ?string
    {
        return $this->post?->exists ? 'Редактировать публикацию' : 'Создать публикацию';
    }

    public function permission(): ?iterable
    {
        return ['platform.posts'];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Удалить')
                ->icon('bs.trash3')
                ->confirm('Публикация будет удалена безвозвратно.')
                ->method('remove')
                ->canSee($this->post?->exists ?? false),

            Button::make('Сохранить')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(PostEditLayout::class)
                ->title('Публикация')
                ->commands(
                    Button::make('Сохранить')
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->method('save')
                ),
        ];
    }

    public function save(Post $post, SavePostRequest $request): RedirectResponse
    {
        $this->postService->save($post, $request->validated()['post']);

        Toast::info('Публикация сохранена.');

        return redirect()->route('platform.posts');
    }

    public function remove(Post $post): RedirectResponse
    {
        $this->postService->remove($post);

        Toast::info('Публикация удалена.');

        return redirect()->route('platform.posts');
    }
}
