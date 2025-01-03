<?php

use App\Models\User;

use function Pest\Laravel\postJson;

test('user can register with valid data', function () {
    $data = [
        'name' => fake()->name(),
        'email' => fake()->safeEmail(),
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    postJson(route('register'), $data)
        ->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'token',
            ],
        ]);
});

test('email, name, and password are required for registration', function () {
    postJson(route('register'))
        ->assertJsonValidationErrors([
            'name',
            'email',
            'password',
        ]);
});

test('password must be confirmed for registration', function () {
    postJson(route('register'), [
        'name' => fake()->name(),
        'email' => fake()->safeEmail(),
        'password' => 'password',
    ])
        ->assertJsonValidationErrorFor('password');
});

test('email must be unique for registration', function () {
    $user = User::factory()->create();

    postJson(route('register'), [
        'name' => fake()->name(),
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertJsonValidationErrorFor('email');
});
