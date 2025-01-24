<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Работа',
                'icon' => 'briefcase',
                'color' => '#4F46E5'
            ],
            [
                'name' => 'Личное',
                'icon' => 'user',
                'color' => '#10B981'
            ],
            [
                'name' => 'Важное',
                'icon' => 'star',
                'color' => '#EF4444'
            ],
            [
                'name' => 'Идеи',
                'icon' => 'light-bulb',
                'color' => '#F59E0B'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'user_id' => 1, // ID пользователя из UserSeeder
                'name' => $category['name'],
                'icon' => $category['icon'],
                'color' => $category['color']
            ]);
        }
    }
}
