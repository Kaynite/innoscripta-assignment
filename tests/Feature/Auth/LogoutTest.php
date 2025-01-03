<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

test('user can logout', function () {
    Sanctum::actingAs(User::factory()->create());

    postJson(route('logout'))
        ->assertNoContent();
});

test('unauthenticated user cannot logout', function () {
    postJson(route('logout'))
        ->assertUnauthorized();
});
