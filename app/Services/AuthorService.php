<?php

namespace App\Services;

use App\Models\Author;

class AuthorService
{
    public function getAuthors()
    {
        return Author::query()
            ->latest('id')
            ->paginate();
    }

    public function getAuthorById($id)
    {
        return Author::findOrFail($id);
    }
}
