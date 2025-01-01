<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'preferences' => [
                'sources' => $this->preferred_sources,
                'categories' => CategoryResource::collection($this->whenLoaded('categories')),
                'authors' => AuthorResource::collection($this->whenLoaded('authors')),
            ],
        ];
    }
}
