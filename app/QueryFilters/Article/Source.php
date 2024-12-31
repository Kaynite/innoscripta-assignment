<?php

namespace App\QueryFilters\Article;

class Source
{
    public function handle($query, $next)
    {
        $source = request()->input('source');

        $query->when($source, function ($query) use ($source) {
            $query->where('source', $source);
        });

        return $next($query);
    }
}
