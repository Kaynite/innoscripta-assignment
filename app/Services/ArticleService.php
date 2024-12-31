<?php

namespace App\Services;

use App\Models\Article;
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
}
