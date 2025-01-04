<?php

namespace App\Console\Commands;

use App\Enums\ArticleSource;
use App\Jobs\FetchNewsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
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

        $saveQuery = confirm(
            label: 'Do you want to regularly fetch news for this query and save them to the database?',
            default: true,
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

        if ($saveQuery) {
            $this->saveQuery($query, $sources);
        }

        foreach ($sources as $source) {
            $source = ArticleSource::from($source);
            $this->info("Fetching news from {$source->label()}...");
            $this->fetchNews($query, $source, $numberOfArticles, $from, $to);
        }
    }

    public function fetchNews(string $query, ArticleSource $source, int $numberOfArticles, ?string $from, ?string $to)
    {
        FetchNewsJob::dispatchSync(
            query: $query,
            source: $source,
            numberOfArticles: $numberOfArticles,
            from: $from,
            to: $to,
        );
    }

    public function saveQuery($query, $sources)
    {
        $filePath = storage_path('app/queries.json');
        $queries = File::exists($filePath) ? File::json($filePath, lock: true) : [];

        if (! isset($queries[$query])) {
            $queries[$query] = $sources;
            File::put($filePath, json_encode($queries));
        }
    }
}
