<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {

        // Ana kategoriler
        $category1 = Category::create([
            'name' => 'Tech', // Kategori adı
            'parent_id' => null, // Ana kategori, parent_id null
        ]);


        // Alt kategoriler (parent_id'yi belirtiyoruz)
        Category::create([
            'name' => 'Programming', // Alt kategori adı
            'parent_id' => $category1->id, // 'Tech' kategorisinin alt kategorisi
        ]);
    }
}
