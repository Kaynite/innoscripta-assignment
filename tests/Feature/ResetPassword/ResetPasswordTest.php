<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

test('user can request reset password code', function () {
    $user = User::factory()->create();

    postJson(route('password.forgot'), [
        'email' => $user->email,
    ])
        ->assertOk()
        ->assertJsonPath('message', 'A password reset code will be sent to your email if it exists in our database.');

    assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

test('The same message will eb returned if the email does not exist in the database', function () {
    postJson(route('password.forgot'), [
        'email' => 'invalid-email@example.com',
    ])
        ->assertOk()
        ->assertJsonPath('message', 'A password reset code will be sent to your email if it exists in our database.');
});

test('email is required to request reset password code', function () {
    postJson(route('password.forgot'))
        ->assertJsonValidationErrorFor('email');
});

test('user can reset password', function () {
    $user = User::factory()->create();

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => $token = fake()->numerify('######'),
        'created_at' => now(),
    ]);

    postJson(route('password.reset'), [
        'email' => $user->email,
        'token' => $token,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])
        ->assertOk()
        ->assertJsonPath('message', 'Password reset successfully.');

    expect($user->password)->not->toBe($user->fresh()->password);
});

test('data is required to reset password', function () {
    postJson(route('password.reset'))
        ->assertJsonValidationErrors(['email', 'token', 'password']);
});

test('An error message is returned if email or code are invalid', function () {
    $user = User::factory()->create();

    postJson(route('password.reset'), [
        'email' => $user->email,
        'token' => 'invalid-token',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', 'The password reset token is invalid or expired.');
});
