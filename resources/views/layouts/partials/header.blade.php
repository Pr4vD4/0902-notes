<nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-sm z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Логотип -->
            <div class="flex items-center">
                <a href="/" class="text-primary font-bold text-xl">
                    MyNotes
                </a>
            </div>

            <!-- Кнопки авторизации/профиля -->
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('diary') }}"
                       class="text-gray-600 hover:text-primary transition">
                        Мой дневник
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-gray-600 hover:text-primary transition">
                            Выйти
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-600 hover:text-primary transition">
                        вход
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition">
                        регистрация
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
