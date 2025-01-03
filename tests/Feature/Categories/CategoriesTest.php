<?php

use App\Models\Category;

use function Pest\Laravel\getJson;

test('users can list categories', function () {
    Category::factory()->count(3)->create();

    getJson(route('categories.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('user can view a category', function () {
    $category = Category::factory()->create();

    getJson(route('categories.show', $category))
        ->assertOk()
        ->assertJsonPath('data.id', $category->id);
});
