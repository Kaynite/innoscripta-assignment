<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\Response as ApiResponse;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Auth
 */
class AuthController extends Controller
{
    /**
     * Login
     */
    #[ResponseFromApiResource(AuthResource::class, User::class)]
    public function login(LoginRequest $request): AuthResource
    {
        $user = User::firstWhere('email', $request->email);

        throw_unless(
            $user && Hash::check($request->password, $user->password),
            ValidationException::withMessages(['email' => __('auth.failed')]),
        );

        return AuthResource::make($user);
    }

    /**
     * Register
     */
    #[ResponseFromApiResource(AuthResource::class, User::class)]
    public function register(RegisterRequest $request): AuthResource
    {
        $user = User::create($request->validated());

        return AuthResource::make($user);
    }

    /**
     * Logout
     */
    #[ApiResponse(status: 204)]
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

    /**
     * Me
     */
    #[ResponseFromApiResource(UserResource::class, User::class)]
    public function me(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }
}
