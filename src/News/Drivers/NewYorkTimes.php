<?php

namespace Innoscripta\News\Drivers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Innoscripta\News\DataTransferObjects\NewsArticle;

class NewYorkTimes
{
    protected $apiKey;

    protected $baseUrl = 'https://api.nytimes.com/svc/search/v2';

    public function __construct()
    {
        $this->apiKey = config('news.drivers.new_york_times.api_key');
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
            ->get('articlesearch.json', [
                'api-key' => $this->apiKey,
                'q' => $query,
                'sort' => 'newest',
                'pageSize' => $numberOfArticles,
                'begin_date' => $from?->format('Ymd'),
                'end_date' => $to?->format('Ymd'),
            ])
            ->collect('response.docs')
            ->map(fn ($article) => NewsArticle::fromNewYorkTimes($article));
    }
}
