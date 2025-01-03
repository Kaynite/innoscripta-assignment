<?php

use App\Models\Article;

use function Pest\Laravel\getJson;

test('users can list articles', function () {
    Article::factory()->count(3)->create();

    getJson(route('articles.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('user can view an article', function () {
    $article = Article::factory()->create();

    getJson(route('articles.show', $article))
        ->assertOk()
        ->assertJsonPath('data.id', $article->id);
});
