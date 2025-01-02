<?php

namespace Innoscripta\News\Drivers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Innoscripta\News\DataTransferObjects\NewsArticle;

class NewsApi
{
    protected $apiKey;

    protected $baseUrl = 'https://newsapi.org/v2';

    public function __construct()
    {
        $this->apiKey = config('news.drivers.newsapi.key');
    }

    /**
     * Get news articles from the News API.
     *
     * @return Collection<NewsArticle>
     */
    public function getNews(
        string $query,
        int $numberOfArticles = 100,
        ?Carbon $from = null,
        ?Carbon $to = null,
    ): Collection {
        return Http::baseUrl($this->baseUrl)
            ->get('everything', [
                'apiKey' => $this->apiKey,
                'q' => $query,
                'sortBy' => 'publishedAt',
                'pageSize' => $numberOfArticles,
                'from' => $from?->format('Y-m-dTH:i:s'),
                'to' => $to?->format('Y-m-dTH:i:s'),
            ])
            ->collect('articles')
            ->map(fn ($article) => NewsArticle::fromNewsApi($article));
    }
}
