<?php

namespace App\Models;

use App\Enums\ArticleSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'url',
        'image_url',
        'author_id',
        'category_id',
        'source',
        'published_at',
    ];

    protected $casts = [
        'source' => ArticleSource::class,
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
