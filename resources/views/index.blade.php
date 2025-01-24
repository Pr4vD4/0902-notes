@extends('layouts.guest')

@section('content')
    <!-- Основной контент -->
    <div class="min-h-screen flex flex-col items-center justify-center px-4 bg-gradient-to-br from-primary/5 via-purple-100 to-primary/5">
        <div class="text-center max-w-2xl">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4 bg-gradient-to-r from-primary to-purple-600 bg-clip-text text-transparent">
                Добро пожаловать в MyNotes
            </h1>
            <p class="text-xl sm:text-2xl text-gray-600 mb-8">
                - ваш личный дневник
            </p>

            @auth
                <p class="text-gray-500 mb-12">
                    Перейдите в ваш личный дневник для создания записей
                </p>
                <a href="{{ route('diary') }}"
                   class="inline-block px-8 py-3 bg-white text-primary border-2 border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
                    Мой дневник
                </a>
            @else
                <p class="text-gray-500 mb-12">
                    чтобы создать запись - авторизуйтесь
                </p>
                <a href="{{ route('login') }}"
                   class="inline-block px-8 py-3 bg-white text-primary border-2 border-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
                    вход
                </a>
            @endauth
        </div>

        <!-- Декоративный элемент -->
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-purple-100/40 via-transparent to-transparent"></div>
        </div>
    </div>
@endsection
