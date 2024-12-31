<?php

namespace App\QueryFilters\Article;

use Illuminate\Database\Eloquent\Builder;

class Author
{
    public function handle(Builder $query, $next)
    {
        $author = request()->input('author');

        $query->when($author, function ($query) use ($author) {
            $query->where('author_id', $author);
        });

        return $next($query);
    }
}
