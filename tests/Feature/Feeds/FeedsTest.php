<?php

use App\Enums\ArticleSource;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\User;

use function Pest\Laravel\be;

beforeEach(function () {
    $this->user = User::factory()->create([
        'preferred_sources' => [ArticleSource::NewsApi->value],
    ]);
});

test('users can view his feeds', function () {
    foreach (ArticleSource::cases() as $source) {
        Article::factory()->count(5)->create([
            'source' => $source,
        ]);
    }

    be($this->user)
        ->getJson(route('feed'))
        ->assertOk()
        ->assertJsonCount(5, 'data');
});

test('users can view his feeds by category', function () {
    $category = Category::factory()->create();
    $this->user->categories()->attach($category);

    Article::factory()->count(5)->create([
        'category_id' => $category->id,
        'source' => ArticleSource::NewYorkTimes->value,
    ]);

    Article::factory()->count(5)->create([
        'source' => ArticleSource::NewYorkTimes->value,
    ]);

    be($this->user)
        ->getJson(route('feed'))
        ->assertOk()
        ->assertJsonCount(5, 'data');
});

test('users can view his feeds by author', function () {
    $author = Author::factory()->create();
    $this->user->authors()->attach($author);

    Article::factory()->count(5)->create([
        'author_id' => $author->id,
        'source' => ArticleSource::NewYorkTimes->value,
    ]);

    Article::factory()->count(5)->create([
        'source' => ArticleSource::NewYorkTimes->value,
    ]);

    be($this->user)
        ->getJson(route('feed'))
        ->assertOk()
        ->assertJsonCount(5, 'data');
});
