<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Feed
 *
 * @authenticated
 */
class FeedController extends Controller
{
    /**
     * Get the user feed.
     */
    #[ResponseFromApiResource(ArticleResource::class, Article::class, collection: true, paginate: true)]
    public function __invoke(ArticleService $userService, Request $request)
    {
        $feed = $userService->getUserFeed($request->user());

        return ArticleResource::collection($feed);
    }
}
