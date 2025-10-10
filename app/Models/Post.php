<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    public const STATUS = [
        'DRAFT' => 0,
        'PUBLISHED' => 1,
    ];

    protected $fillable = [
        'slug',
        'title',
        'content',
        'excerpt',
        'thumbnail_id',
        'status',
        'published_at',
    ];
}
