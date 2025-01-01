<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Update user preferences.
     *
     * @param  User  $user
     * @param  string[]  $sources
     * @param  int[]  $categories
     * @param  int[]  $authors
     */
    public function updatePreferences($user, $sources, $categories, $authors): User
    {
        $user->update(['preferred_sources' => $sources]);
        $user->categories()->sync($categories);
        $user->authors()->sync($authors);

        return $user;
    }
}
