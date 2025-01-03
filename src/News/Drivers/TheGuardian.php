<?php

namespace Innoscripta\News\Drivers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Innoscripta\News\DataTransferObjects\NewsArticle;

class TheGuardian
{
    protected $apiKey;

    protected $baseUrl = 'https://content.guardianapis.com';

    public function __construct()
    {
        $this->apiKey = config('news.drivers.the_guardian.api_key');
    }

    /**
     * Get news articles from the News API.
     *
     * @return Collection<NewsArticle>
     */
    public function getNews(
        string $query,
        int $numberOfArticles = 50,
        ?Carbon $from = null,
        ?Carbon $to = null,
    ): Collection {
        return Http::baseUrl($this->baseUrl)
            ->get('search', [
                'api-key' => $this->apiKey,
                'q' => $query,
                'order-by' => 'newest',
                'page-size' => $numberOfArticles,
                'from-date' => $from?->toDateString(),
                'to-date' => $to?->toDateString(),
                'show-fields' => 'body,byline,thumbnail',
            ])
            ->collect('response.results')
            ->map(fn ($article) => NewsArticle::fromTheGuardian($article));
    }
}
