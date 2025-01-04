<?php

namespace App\Http\Requests;

use App\Enums\ArticleSource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListArticlesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keyword' => ['sometimes', 'string'],
            'source' => ['sometimes', Rule::enum(ArticleSource::class)],
            'category' => ['sometimes', 'exists:categories,id'],
            'author' => ['sometimes', 'exists:authors,id'],
            'date' => ['sometimes', 'date'],
        ];
    }

    // @codeCoverageIgnoreStart
    public function queryParameters(): array
    {
        return [
            'keyword' => [
                'description' => 'Search for articles containing this keyword',
                'example' => 'puppies',
            ],
            'source' => [
                'description' => 'Filter articles by source',
                'example' => ArticleSource::NewsApi->value,
            ],
            'category' => [
                'description' => 'Filter articles by category',
                'example' => 1,
            ],
            'author' => [
                'description' => 'Filter articles by author',
                'example' => 1,
            ],
            'date' => [
                'description' => 'Filter articles by publish date',
                'example' => '2021-01-01',
            ],
        ];
    }
    // @codeCoverageIgnoreEnd

}
