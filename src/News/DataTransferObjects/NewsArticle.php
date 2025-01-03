<?php

namespace Innoscripta\News\DataTransferObjects;

use Carbon\Carbon;

class NewsArticle
{
    public function __construct(
        public string $title,
        public ?string $content = null,
        public ?string $author = null,
        public ?string $publishedAt = null,
        public ?string $url = null,
        public ?string $imageUrl = null,
        public ?string $category = null,
    ) {
        //
    }

    public static function fromNewsApi(array $article): self
    {
        return new self(
            title: $article['title'],
            content: $article['content'],
            author: $article['author'],
            publishedAt: $article['publishedAt'],
            url: $article['url'],
            imageUrl: $article['urlToImage'],
        );
    }

    public static function fromNewYorkTimes(array $article): self
    {
        $image = collect($article['multimedia'])->first(fn ($media) => $media['type'] === 'image');

        $imageUrl = $image ? "https://static01.nyt.com/{$image['url']}" : null;

        return new self(
            title: $article['headline']['main'],
            content: $article['lead_paragraph'],
            author: $article['byline']['original'],
            publishedAt: Carbon::parse($article['pub_date']),
            url: $article['web_url'],
            imageUrl: $imageUrl,
            category: $article['news_desk'],
        );
    }

    public static function fromTheGuardian(array $article): self
    {
        return new self(
            title: $article['webTitle'],
            content: $article['fields']['body'],
            author: $article['fields']['byline'],
            publishedAt: Carbon::parse($article['webPublicationDate']),
            url: $article['webUrl'],
            imageUrl: $article['fields']['thumbnail'],
            category: $article['sectionName'],
        );
    }
}
