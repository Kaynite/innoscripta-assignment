<?php

namespace App\QueryFilters\Article;

use Illuminate\Database\Eloquent\Builder;

class Category
{
    public function handle(Builder $query, $next)
    {
        $category = request()->input('category');

        $query->when($category, function ($query) use ($category) {
            $query->where('category_id', $category);
        });

        return $next($query);
    }
}
