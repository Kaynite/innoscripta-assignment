<?php

namespace Innoscripta\News;

use Illuminate\Support\Manager;
use Innoscripta\News\Drivers\NewsApi;
use Innoscripta\News\Drivers\NewYorkTimes;
use Innoscripta\News\Drivers\TheGuardian;

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

    public function createTheGuardianDriver(): TheGuardian
    {
        return new TheGuardian;
    }
}
