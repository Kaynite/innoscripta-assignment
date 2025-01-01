<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Categories
 *
 * @unauthenticated
 */
class CategoryController extends Controller
{
    /**
     * List categories
     */
    #[ResponseFromApiResource(CategoryResource::class, Category::class, collection: true, paginate: true)]
    public function index(CategoryService $categoryService)
    {
        return CategoryResource::collection($categoryService->getCategories());
    }

    /**
     * Show a category
     */
    #[ResponseFromApiResource(CategoryResource::class, Category::class)]
    public function show($id, CategoryService $categoryService)
    {
        return CategoryResource::make($categoryService->getCategoryById($id));
    }
}
