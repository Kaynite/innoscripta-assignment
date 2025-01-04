<?php

namespace App\Jobs;

use App\Enums\ArticleSource;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Innoscripta\News\Facades\News;

use function Laravel\Prompts\progress;
use function Laravel\Prompts\warning;

class FetchNewsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $query,
        public ArticleSource $source,
        public int $numberOfArticles,
        public ?string $from,
        public ?string $to
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $news = News::driver($this->source->value)->getNews(
            query: $this->query,
            numberOfArticles: $this->numberOfArticles,
            from: $this->from ? Carbon::parse($this->from) : null,
            to: $this->to ? Carbon::parse($this->to) : null,
        );

        if ($news->isEmpty()) {
            warning("No news articles found for {$this->source->label()}.");

            return;
        }

        progress(
            label: "Fetching news articles for {$this->source->label()}...",
            steps: $news,
            callback: function ($article) {
                Article::firstOrCreate([
                    'title' => $article->title,
                    'source' => $this->source,
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
