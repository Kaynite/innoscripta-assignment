<?php

namespace App\Console\Commands;

use App\Enums\ArticleSource;
use App\Jobs\FetchNewsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FetchNewsForSavedQueriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-for-queries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news articles for saved queries.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queries = $this->getSavedQueries();

        if (empty($queries)) {
            $this->info('No saved queries found.');

            return;
        }

        foreach ($queries as $query => $sources) {
            foreach ($sources as $source) {
                FetchNewsJob::dispatch(
                    query: $query,
                    source: ArticleSource::from($source),
                    numberOfArticles: 10,
                    from: now()->subHour()->toDateTimeString(),
                    to: now()->toDateTimeString(),
                );
            }
        }
    }

    private function getSavedQueries(): array
    {
        return Storage::json('queries.json') ?? [];
    }
}
