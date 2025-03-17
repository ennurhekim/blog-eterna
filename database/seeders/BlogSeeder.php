<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // İlk kullanıcıyı al (Yoksa user seeder çalıştır)

        if (!$user) {
            $this->command->info('Önce bir kullanıcı oluşturmalısınız.');
            return;
        }

        $blogs = [
            [
                'title' => 'Laravel ile Blog Yönetimi',
                'content' => 'Laravel ile nasıl bir blog oluşturabileceğinizi detaylı olarak anlatıyoruz.',
                'cover_image' => 'uploads/blog1.jpg',
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'PHP 8 Yenilikleri',
                'content' => 'PHP 8 ile gelen yenilikleri keşfedin.',
                'cover_image' => 'uploads/blog2.jpg',
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Next.js vs Laravel',
                'content' => 'Frontend ve backend karşılaştırması: Next.js mi, Laravel mi?',
                'cover_image' => 'uploads/blog3.jpg',
                'published_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create([
                'title' => $blog['title'],
                'slug' => Str::slug($blog['title']),
                'content' => $blog['content'],
                'cover_image' => $blog['cover_image'],
                'published_at' => $blog['published_at'],
                'user_id' => $user->id,
            ]);
        }

        //  $this->command->info('Bloglar başarıyla eklendi.');
    }
}
