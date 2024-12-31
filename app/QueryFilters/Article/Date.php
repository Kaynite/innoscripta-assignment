<?php

namespace App\QueryFilters\Article;

class Date
{
    public function handle($query, $next)
    {
        $date = request()->input('date');

        $query->when($date, function ($query) use ($date) {
            $query->whereDate('published_at', $date);
        });

        return $next($query);
    }
}
