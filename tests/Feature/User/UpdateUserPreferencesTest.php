<?php

use App\Enums\ArticleSource;
use App\Models\Author;
use App\Models\Category;

use function Pest\Laravel\putJson;

beforeEach(function () {
    $categories = Category::factory()->count(5)->create();
    $authors = Author::factory()->count(5)->create();
    $sources = collect(ArticleSource::cases())->map->value;

    $this->data = [
        'sources' => fake()->randomElements($sources, 2),
        'categories' => fake()->randomElements($categories->pluck('id')->toArray(), 2),
        'authors' => fake()->randomElements($authors->pluck('id')->toArray(), 2),
    ];
});

test('user can update his preferences', function () {
    asUser()
        ->putJson(route('users.update'), $this->data)
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'preferences' => [
                    'sources',
                    'categories',
                    'authors',
                ],
            ],
        ]);
});

test('only authenticated user can update his preferences', function () {
    putJson(route('users.update'), $this->data)
        ->assertUnauthorized();
});

test('sources must be an array of valid sources', function () {
    $this->data['sources'] = ['invalid-source'];

    asUser()
        ->putJson(route('users.update'), $this->data)
        ->assertJsonValidationErrorFor('sources.0');
});

test('categories must be an array of valid categories', function () {
    $this->data['categories'] = [999];

    asUser()
        ->putJson(route('users.update'), $this->data)
        ->assertJsonValidationErrorFor('categories.0');
});

test('authors must be an array of valid authors', function () {
    $this->data['authors'] = [999];

    asUser()
        ->putJson(route('users.update'), $this->data)
        ->assertJsonValidationErrorFor('authors.0');
});
