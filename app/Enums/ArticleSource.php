<?php

namespace App\Enums;

enum ArticleSource: string
{
    case NewsApi = 'newsapi';

    case NewYorkTimes = 'new_york_times';

    public function label(): string
    {
        return match ($this) {
            ArticleSource::NewsApi => 'NewsAPI',
            ArticleSource::NewYorkTimes => 'New York Times',
        };
    }
}
