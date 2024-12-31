<?php

namespace App\QueryFilters\Article;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Keyword
{
    public function handle(Builder $builder, $next)
    {
        $keyword = request()->input('keyword');

        $builder->when($keyword, function ($query) use ($keyword) {
            $keyword = str($keyword)->lower()->trim();
            $query->whereAny([
                DB::raw('LOWER(title)'),
                DB::raw('LOWER(content)'),
            ], 'LIKE', "%$keyword%");
        });

        return $next($builder);
    }
}
