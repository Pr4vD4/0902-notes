<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notes = [
            [
                'title' => 'Первая заметка',
                'content' => 'Это моя первая заметка в дневнике. Начинаю вести записи.',
                'category_id' => 2, // Категория "Личное"
                'date' => now(),
            ],
            [
                'title' => 'Важная встреча',
                'content' => 'Запланирована встреча с клиентом на следующей неделе.',
                'category_id' => 1, // Категория "Работа"
                'date' => now()->addDays(7),
            ],
            [
                'title' => 'Идея для проекта',
                'content' => 'Создать приложение для ведения личного дневника онлайн.',
                'category_id' => 3, // Категория "Идеи"
                'date' => now()->subDays(2),
            ],
        ];

        foreach ($notes as $note) {
            Note::create([
                'user_id' => 1, // ID тестового пользователя
                ...$note
            ]);
        }
    }
}
