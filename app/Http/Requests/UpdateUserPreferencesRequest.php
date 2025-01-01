<?php

namespace App\Http\Requests;

use App\Enums\ArticleSource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserPreferencesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sources' => ['array'],
            'sources.*' => ['string', Rule::enum(ArticleSource::class)],
            'categories' => ['array'],
            'categories.*' => [Rule::exists('categories', 'id')],
            'authors' => ['array'],
            'authors.*' => [Rule::exists('authors', 'id')],
        ];
    }
}
