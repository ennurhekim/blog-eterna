<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Auth;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, LogsActivity;

    protected $fillable = ['title', 'slug', 'content', 'cover_image', 'published_at', 'user_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Blog kaydı {$eventName} yapıldı")
            ->useLogName('blog');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            $originalSlug = Str::slug($blog->title);
            $slug = $originalSlug;
            $count = 1;
            while (Blog::where('slug', $slug)->whereNull('deleted_at')->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $blog->slug = $slug;
        });
        static::updating(function ($blog) {
            if ($blog->isDirty('title')) {
                $originalSlug = Str::slug($blog->title);
                $slug = $originalSlug;
                $count = 1;
                while (Blog::where('slug', $slug)->whereNull('deleted_at')->where('id', '!=', $blog->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }

                $blog->slug = $slug;
            }
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
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
