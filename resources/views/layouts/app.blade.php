<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MyNotes') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex flex-col bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('diary') }}" class="text-primary font-bold text-xl">
                        MyNotes
                    </a>
                </div>

                <div class="flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-primary transition">
                            Выйти
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="flex-1">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
</body>
</html>
