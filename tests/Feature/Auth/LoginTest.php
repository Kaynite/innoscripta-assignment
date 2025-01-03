<?php

use App\Models\User;

use function Pest\Laravel\postJson;

test('user can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt($password = 'password'),
    ]);

    postJson(route('login'), [
        'email' => $user->email,
        'password' => $password,
    ])
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'token',
            ],
        ]);
});

test('user cannot login with invalid credentials', function () {
    postJson(route('login'), [
        'email' => 'user@example.com',
        'password' => 'password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('email and password are required for login', function () {
    postJson(route('login'))
        ->assertJsonValidationErrors([
            'email',
            'password',
        ]);
});
