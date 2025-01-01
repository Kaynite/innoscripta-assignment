<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPreferencesRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group User
 *
 * @authenticated
 */
class UserController extends Controller
{
    /**
     * Get user information
     */
    #[ResponseFromApiResource(UserResource::class, User::class)]
    public function me(Request $request): UserResource
    {
        return UserResource::make(
            $request->user()->load('categories', 'authors')
        );
    }

    /**
     * Update user preferences
     */
    #[ResponseFromApiResource(UserResource::class, User::class)]
    public function update(UpdateUserPreferencesRequest $request, UserService $userService): UserResource
    {
        $user = $userService->updatePreferences(
            user: $request->user(),
            sources: $request->input('sources', []),
            categories: $request->input('categories', []),
            authors: $request->input('authors', []),
        );

        $user->load('categories', 'authors');

        return UserResource::make($user);
    }
}
