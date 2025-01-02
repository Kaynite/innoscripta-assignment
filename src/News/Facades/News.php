<?php

namespace Innoscripta\News\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Innoscripta\News\DataTransferObjects\NewsArticle;

/**
 * @method static Collection<NewsArticle> getNews(string $query, int $numberOfArticles = 100, ?string $from = null, ?string $to = null)
 * @method static self driver(string $driver)
 */
class News extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'news';
    }
}
