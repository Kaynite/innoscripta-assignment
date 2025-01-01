<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getCategories()
    {
        return Category::query()
            ->latest('id')
            ->paginate();
    }

    public function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }
}
