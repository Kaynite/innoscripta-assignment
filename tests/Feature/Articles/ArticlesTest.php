<?php

use App\Enums\ArticleSource;
use App\Models\Article;

use function Pest\Laravel\getJson;

test('users can list articles', function () {
    Article::factory()->count(3)->create();

    getJson(route('articles.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('user can filter articles by source', function () {
    Article::factory()->count(3)->create();
    $source = ArticleSource::NewsApi->value;

    getJson(route('articles.index', ['source' => $source]))
        ->assertOk()
        ->assertJsonCount(Article::whereSource($source)->count(), 'data');
});

test('user can filter articles by category', function () {
    Article::factory()->count(3)->create();
    $category = Article::first()->category_id;

    getJson(route('articles.index', ['category' => $category]))
        ->assertOk()
        ->assertJsonCount(Article::whereCategoryId($category)->count(), 'data');
});

test('user can filter articles by author', function () {
    Article::factory()->count(3)->create();
    $author = Article::first()->author_id;

    getJson(route('articles.index', ['author' => $author]))
        ->assertOk()
        ->assertJsonCount(Article::whereAuthorId($author)->count(), 'data');
});

test('user can filter articles by date', function () {
    Article::factory()->count(3)->create();
    $date = Article::first()->published_at->format('Y-m-d');

    getJson(route('articles.index', ['date' => $date]))
        ->assertOk()
        ->assertJsonCount(Article::whereDate('published_at', $date)->count(), 'data');
});

test('user can search for articles', function () {
    Article::factory()->count(3)->create();
    $keyword = Article::first()->title;

    getJson(route('articles.index', ['keyword' => $keyword]))
        ->assertOk()
        ->assertJsonCount(Article::where('title', 'like', "%$keyword%")->count(), 'data');
});

test('data must be valid when filtering articles', function () {
    getJson(route('articles.index', [
        'source' => 'invalid-source',
        'category' => 'invalid-category',
        'author' => 'invalid-author',
        'date' => 'invalid-date',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'source',
            'category',
            'author',
            'date',
        ]);
});

test('user can view an article', function () {
    $article = Article::factory()->create();

    getJson(route('articles.show', $article))
        ->assertOk()
        ->assertJsonPath('data.id', $article->id);
});
