<?php

use function Pest\Laravel\getJson;

test('user can get his info', function () {
    asUser()
        ->getJson(route('users.me'))
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

test('only authenticated user can get his info', function () {
    getJson(route('users.me'))
        ->assertUnauthorized();
});
