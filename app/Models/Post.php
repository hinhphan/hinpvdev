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

    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'integer',
    ];

    // Relationships
    public function thumbnail()
    {
        return $this->belongsTo(File::class, 'thumbnail_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id')
            ->withTimestamps();
    }

    public function seoMeta()
    {
        return $this->hasOne(SeoMeta::class, 'post_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS['PUBLISHED'])
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS['DRAFT']);
    }
}
