<?php

namespace Database\Factories;

use App\Enums\ArticleSource;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'url' => fake()->url(),
            'image_url' => fake()->imageUrl(),
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'source' => fake()->randomElement(ArticleSource::cases()),
            'published_at' => fake()->dateTime(),
        ];
    }
}
