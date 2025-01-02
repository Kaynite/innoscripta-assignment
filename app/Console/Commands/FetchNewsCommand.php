<?php

namespace App\Console\Commands;

use App\Enums\ArticleSource;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Innoscripta\News\Facades\News;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\progress;
use function Laravel\Prompts\text;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news articles from different sources.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = text(
            label: 'Enter the news you want to search for:',
            placeholder: 'e.g. Laravel',
            required: true
        );

        $sources = multiselect(
            label: 'Select the sources you want to search from:',
            options: collect(ArticleSource::cases())->mapWithKeys(fn ($source) => [$source->value => $source->label()]),
            required: true
        );

        $numberOfArticles = text(
            label: 'Enter the number of articles you want to fetch:',
            placeholder: 'e.g. 10',
            required: true,
            validate: ['integer', 'min:1', 'max:100'],
        );

        $from = text(
            label: 'Enter the date from which you want to fetch the articles:',
            placeholder: 'e.g. 2021-01-01',
            required: false,
            validate: ['date'],
        );

        $to = text(
            label: 'Enter the date to which you want to fetch the articles:',
            placeholder: 'e.g. 2021-12-31',
            required: false,
            validate: ['date'],
        );

        foreach ($sources as $source) {
            $source = ArticleSource::from($source);
            $this->info("Fetching news from {$source->label()}...");
            $this->fetchNews($query, $source, $numberOfArticles, $from, $to);
        }
    }

    protected function fetchNews(string $query, ArticleSource $source, int $numberOfArticles, ?string $from, ?string $to)
    {
        $news = News::driver($source->value)->getNews(
            query: $query,
            numberOfArticles: $numberOfArticles,
            from: $from ? Carbon::parse($from) : null,
            to: $to ? Carbon::parse($to) : null,
        );

        if ($news->isEmpty()) {
            $this->warn("No news articles found for {$source->label()}.");

            return;
        }

        progress(
            label: "Fetching news articles for {$source->label()}...",
            steps: $news,
            callback: function ($article) use ($source) {
                Article::firstOrCreate([
                    'title' => $article->title,
                    'source' => $source,
                ], [
                    'content' => $article->content,
                    'url' => $article->url,
                    'image_url' => $article->imageUrl,
                    'author_id' => $article->author ? Author::firstOrCreate(['name' => $article->author])->id : null,
                    'category_id' => $article->category ? Category::firstOrCreate(['name' => $article->category])->id : null,
                    'published_at' => Carbon::parse($article->publishedAt),
                ]);
            },
            hint: 'This may take some time.'
        );
    }
}
