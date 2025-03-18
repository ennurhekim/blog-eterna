<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $originalSlug = Str::slug($category->name);
            $slug = $originalSlug;
            $count = 1;
    
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
    
            $category->slug = $slug;
        });
    }

    // Alt kategorileri getiren ilişki (recursive)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children'); // Burada recursive çağrı yapılıyor
    }

    // Üst kategoriyi getiren ilişki
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category');
    }
}
