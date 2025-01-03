<?php

use App\Models\Author;

use function Pest\Laravel\getJson;

test('users can list authors', function () {
    Author::factory()->count(3)->create();

    getJson(route('authors.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('user can view an author', function () {
    $author = Author::factory()->create();

    getJson(route('authors.show', $author))
        ->assertOk()
        ->assertJsonPath('data.id', $author->id);
});
