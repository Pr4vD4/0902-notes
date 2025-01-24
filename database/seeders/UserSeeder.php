<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем тестового пользователя из ТЗ
        User::create([
            'name' => 'Тестовый пользователь',
            'phone' => '79991234567',
            'login' => 'mynotes2024',
            'password' => Hash::make('20242024'),
        ]);
    }
}
