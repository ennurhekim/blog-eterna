<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'cover_image', 'published_at', 'user_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $originalSlug = Str::slug($blog->title); // Başlığı slug'a çevir
            $slug = $originalSlug;
            $count = 1;

            while (Blog::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $blog->slug = $slug;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }
}
