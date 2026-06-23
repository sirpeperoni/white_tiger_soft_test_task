<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Post;

use App\Models\Post;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PostListLayout extends Table
{
    public $target = 'posts';

    public function columns(): array
    {
        return [
            TD::make('title', 'Заголовок')
                ->sort()
                ->cantHide(),

            TD::make('user.name', 'Автор')
                ->render(fn (Post $post) => $post->user->name),

            TD::make('created_at', 'Дата публикации')
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),

            TD::make('Действия')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Post $post) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Редактировать')
                            ->route('platform.posts.edit', ['post' => $post->id])
                            ->icon('bs.pencil'),

                        Button::make('Удалить')
                            ->icon('bs.trash3')
                            ->confirm('Публикация будет удалена безвозвратно.')
                            ->method('remove', ['id' => $post->id]),
                    ])),
        ];
    }
}
