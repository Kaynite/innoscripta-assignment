<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Services\AuthorService;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Authors
 *
 * @unauthenticated
 */
class AuthorController extends Controller
{
    /**
     * List authors
     */
    #[ResponseFromApiResource(AuthorResource::class, Author::class, collection: true, paginate: true)]
    public function index(AuthorService $authorService)
    {
        return AuthorResource::collection($authorService->getAuthors());
    }

    /**
     * Show an author
     */
    #[ResponseFromApiResource(AuthorResource::class, Author::class)]
    public function show($id, AuthorService $authorService)
    {
        return AuthorResource::make($authorService->getAuthorById($id));
    }
}
