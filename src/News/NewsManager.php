<?php

namespace Innoscripta\News;

use Illuminate\Support\Manager;
use Innoscripta\News\Drivers\NewsApi;
use Innoscripta\News\Drivers\NewYorkTimes;

class NewsManager extends Manager
{
    public function getDefaultDriver()
    {
        return config('news.default', 'newsapi');
    }

    public function createNewsapiDriver(): NewsApi
    {
        return new NewsApi;
    }

    public function createNewYorkTimesDriver(): NewYorkTimes
    {
        return new NewYorkTimes;
    }
}
