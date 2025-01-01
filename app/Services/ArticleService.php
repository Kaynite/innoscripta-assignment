<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Pipeline;

class ArticleService
{
    public function getArticles()
    {
        return Pipeline::send(Article::query())
            ->through([
                \App\QueryFilters\Article\Keyword::class,
                \App\QueryFilters\Article\Author::class,
                \App\QueryFilters\Article\Category::class,
                \App\QueryFilters\Article\Date::class,
                \App\QueryFilters\Article\Source::class,
            ])
            ->thenReturn()
            ->with(['author', 'category'])
            ->orderByDesc('id')
            ->paginate();
    }

    public function getArticleById(int $id): Article
    {
        return Article::with(['author', 'category'])->findOrFail($id);
    }

    public function getUserFeed(User $user)
    {
        return Article::query()
            ->where(function ($query) use ($user) {
                $query->whereHas('author', function ($query) use ($user) {
                    $query->whereIn('id', $user->authors()->pluck('id')->toArray());
                })
                    ->orWhereHas('category', function ($query) use ($user) {
                        $query->whereIn('id', $user->categories()->pluck('id')->toArray());
                    })
                    ->orWhere('source', $user->preferred_sources);
            })
            ->with(['author', 'category'])
            ->latest('id')
            ->paginate();
    }
}
