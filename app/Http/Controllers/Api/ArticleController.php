<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Articles
 */
class ArticleController extends Controller
{
    /**
     * List articles
     */
    #[ResponseFromApiResource(ArticleResource::class, Article::class, collection: true, paginate: true)]
    public function index(ListArticlesRequest $request, ArticleService $articleService): AnonymousResourceCollection
    {
        $articles = $articleService->getArticles();

        return ArticleResource::collection($articles);
    }

    /**
     * Show an article
     */
    #[ResponseFromApiResource(ArticleResource::class, Article::class)]
    public function show($article, ArticleService $articleService): ArticleResource
    {
        $article = $articleService->getArticleById($article);

        return ArticleResource::make($article);
    }
}
