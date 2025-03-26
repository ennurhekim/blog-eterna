<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['user_id', 'blog_id', 'content','status'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Yorum kaydı {$eventName} yapıldı")
            ->useLogName('comment');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
