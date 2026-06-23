<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Post;

use App\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class PostEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('post.title')
                ->title('Заголовок')
                ->placeholder('Введите заголовок публикации')
                ->required(),

            TextArea::make('post.text')
                ->title('Текст')
                ->placeholder('Введите текст публикации')
                ->rows(10)
                ->required(),

            Relation::make('post.user_id')
                ->title('Автор')
                ->fromModel(User::class, 'name')
                ->required(),
        ];
    }
}
