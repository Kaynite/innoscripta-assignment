<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request): AuthResource
    {
        $user = User::firstWhere('email', $request->email);

        throw_unless(
            $user && Hash::check($request->password, $user->password),
            ValidationException::withMessages(['email' => __('auth.failed')]),
        );

        return AuthResource::make($user);
    }

    public function register(RegisterRequest $request): AuthResource
    {
        $user = User::create($request->validated());

        return AuthResource::make($user);
    }

    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
