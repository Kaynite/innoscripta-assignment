<?php

use App\Enums\ArticleSource;
use App\Jobs\FetchNewsJob;
use App\Models\Article;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Innoscripta\News\DataTransferObjects\NewsArticle;
use Innoscripta\News\Facades\News;

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('fetch news command', function () {
    Queue::fake();
    Storage::fake();

    artisan('news:fetch')
        ->expectsQuestion('Enter the news you want to search for:', $query = fake()->word())
        ->expectsQuestion('Select the sources you want to search from:', [ArticleSource::NewsApi->value])
        ->expectsQuestion('Do you want to regularly fetch news for this query and save them to the database?', true)
        ->expectsQuestion('Enter the number of articles you want to fetch:', 10)
        ->expectsQuestion('Enter the date from which you want to fetch the articles:', '2021-01-01')
        ->expectsQuestion('Enter the date to which you want to fetch the articles:', '2021-01-31')
        ->expectsOutput('Fetching news from NewsAPI...')
        ->assertExitCode(0);

    Queue::assertPushed(FetchNewsJob::class);

    $queries = Storage::json('queries.json');

    expect($queries)->toHaveKey($query);

    expect($queries[$query])->toBe([ArticleSource::NewsApi->value]);
});

test('fetched news will be saved to the database', function () {
    Storage::fake();

    $newsDriverMock = Mockery::mock();
    $newsDriverMock->shouldReceive('getNews')
        ->once()
        ->andReturn(collect([
            new NewsArticle(
                title: 'Laravel News',
                content: 'Laravel is a web application framework with expressive, elegant syntax.',
                url: 'https://laravel.com',
            ),
        ]));

    News::shouldReceive('driver')
        ->once()
        ->with(ArticleSource::NewsApi->value)
        ->andReturn($newsDriverMock);

    artisan('news:fetch')
        ->expectsQuestion('Enter the news you want to search for:', fake()->word())
        ->expectsQuestion('Select the sources you want to search from:', [ArticleSource::NewsApi->value])
        ->expectsQuestion('Do you want to regularly fetch news for this query and save them to the database?', true)
        ->expectsQuestion('Enter the number of articles you want to fetch:', 10)
        ->expectsQuestion('Enter the date from which you want to fetch the articles:', null)
        ->expectsQuestion('Enter the date to which you want to fetch the articles:', null)
        ->expectsOutput('Fetching news from NewsAPI...')
        ->assertExitCode(0);

    assertDatabaseHas(Article::class, [
        'title' => 'Laravel News',
        'content' => 'Laravel is a web application framework with expressive, elegant syntax.',
        'url' => 'https://laravel.com',
    ]);
});

test('news wont be saved if nothing was fetched', function () {
    Storage::fake();

    $newsDriverMock = Mockery::mock();
    $newsDriverMock->shouldReceive('getNews')
        ->once()
        ->andReturn(collect());

    News::shouldReceive('driver')
        ->once()
        ->with(ArticleSource::NewsApi->value)
        ->andReturn($newsDriverMock);

    artisan('news:fetch')
        ->expectsQuestion('Enter the news you want to search for:', fake()->word())
        ->expectsQuestion('Select the sources you want to search from:', [ArticleSource::NewsApi->value])
        ->expectsQuestion('Do you want to regularly fetch news for this query and save them to the database?', true)
        ->expectsQuestion('Enter the number of articles you want to fetch:', 10)
        ->expectsQuestion('Enter the date from which you want to fetch the articles:', null)
        ->expectsQuestion('Enter the date to which you want to fetch the articles:', null)
        ->expectsOutput('Fetching news from NewsAPI...')
        ->assertExitCode(0);

    assertDatabaseCount(Article::class, 0);
});
