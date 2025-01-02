<?php

namespace Innoscripta\News\Providers;

use Illuminate\Support\ServiceProvider;
use Innoscripta\News\NewsManager;

class NewsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('news', fn ($app) => new NewsManager($app));
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/news.php', 'news');

        $this->publishes([
            __DIR__.'/../Config/news.php' => config_path('news.php'),
        ], 'config');
    }
}
