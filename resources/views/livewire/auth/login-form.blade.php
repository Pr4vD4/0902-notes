<div class="bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-primary mb-8">Вход</h2>

    <form wire:submit="handleLogin" class="space-y-6">
        <!-- Логин -->
        <div>
            <label for="login" class="block text-sm font-medium text-gray-700">Логин</label>
            <input wire:model="login" type="text" id="login"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            @error('login') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Пароль -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
            <input wire:model="password" type="password" id="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Запомнить меня -->
        <div class="flex items-center">
            <input wire:model="remember" type="checkbox" id="remember"
                   class="rounded border-gray-300 text-primary focus:ring-primary">
            <label for="remember" class="ml-2 block text-sm text-gray-700">
                Запомнить меня
            </label>
        </div>

        <!-- Кнопка входа -->
        <button type="submit"
                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
            Войти
        </button>

        <div class="text-center text-sm text-gray-600">
            Нет аккаунта?
            <a href="{{ route('register') }}" class="text-primary hover:text-primary/90">
                Зарегистрироваться
            </a>
        </div>
    </form>
</div>
