<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Работа',
            'Личное',
            'Идеи',
            'Важное'
        ];

        foreach ($categories as $category) {
            Category::create([
                'user_id' => 1, // ID тестового пользователя
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }
    }
}
