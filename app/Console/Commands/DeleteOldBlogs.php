<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteOldBlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-blogs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subDays(7);

        $blogs = Blog::where('created_at', '<=', $oneWeekAgo)
            ->whereDoesntHave('comments', function ($query) use ($oneWeekAgo) {
                $query->where('created_at', '>', $oneWeekAgo);
            })
            ->limit(100)
            ->get();
        $ids = $blogs->pluck('id')->toArray();
        Blog::whereIn('id', $ids)->delete();
        Log::info("weekly_blog_deleted", ["ids" => $ids]);
    
        return self::SUCCESS;
    }
}
