<?php

use App\Enums\ArticleSource;
use App\Jobs\FetchNewsJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

test('fetch news for saved queries command', function () {
    Queue::fake();
    Storage::fake();

    Storage::put('queries.json', json_encode([
        'Laravel' => [ArticleSource::NewsApi->value],
    ]));

    artisan('news:fetch-for-queries')
        ->assertExitCode(0);

    Queue::assertPushed(FetchNewsJob::class);
});

test('user will receive a message if there are no saved queries', function () {
    Storage::fake();
    Storage::put('queries.json', json_encode([]));

    artisan('news:fetch-for-queries')
        ->expectsOutput('No saved queries found.')
        ->assertExitCode(0);
});
