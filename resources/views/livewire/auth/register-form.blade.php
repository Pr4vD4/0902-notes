<div class="bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-primary mb-8">Регистрация</h2>

    <form wire:submit="register" class="space-y-6">
        <!-- Имя -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
            <input wire:model.live="name" type="text" id="name"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Телефон -->
        <div x-data="{
            phone: '8',
            formatPhone() {
                let digits = this.phone.replace(/\D/g, '');
                if (!digits.startsWith('8')) digits = '8' + digits;
                if (digits.length > 11) digits = digits.substr(0, 11);

                let formatted = '8';
                if (digits.length > 1) formatted += '-' + digits.substr(1, 3);
                if (digits.length > 4) formatted += '-' + digits.substr(4, 3);
                if (digits.length > 7) formatted += '-' + digits.substr(7, 2);
                if (digits.length > 9) formatted += '-' + digits.substr(9);

                this.phone = formatted;
                $wire.phone = digits;
            }
        }"
        x-init="formatPhone()"
        x-on:input="formatPhone()"
        >
            <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
            <input type="tel" id="phone"
                   x-model="phone"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                   placeholder="8-999-123-45-67">
            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Логин -->
        <div>
            <label for="login" class="block text-sm font-medium text-gray-700">Логин</label>
            <input wire:model.live="login" type="text" id="login"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            @error('login') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Пароль -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
            <input wire:model.live="password" type="password" id="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Подтверждение пароля -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля</label>
            <input wire:model.live="password_confirmation" type="password" id="password_confirmation"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
        </div>

        <!-- Согласие -->
        <div class="flex items-center">
            <input wire:model.live="agreement" type="checkbox" id="agreement"
                   class="rounded border-gray-300 text-primary focus:ring-primary">
            <label for="agreement" class="ml-2 block text-sm text-gray-700">
                Согласен на обработку персональных данных
            </label>
        </div>
        @error('agreement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <!-- Кнопка регистрации -->
        <button type="submit"
                class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
            Зарегистрироваться
        </button>

        <div class="text-center text-sm text-gray-600">
            Уже есть аккаунт?
            <a href="{{ route('login') }}" class="text-primary hover:text-primary/90">
                Войти
            </a>
        </div>
    </form>
</div>

<script>
function phoneInput() {
    return {
        displayValue: '',
        phoneNumber: '',
        mask: '8 (###) ###-##-##',

        initInput() {
            this.displayValue = this.mask;
        },

        onInput(event) {
            let input = event.target.value.replace(/\D/g, '');
            let formatted = this.mask;

            for (let i = 0; i < input.length && i < 11; i++) {
                formatted = formatted.replace('#', input[i]);
            }

            this.displayValue = formatted;
            this.phoneNumber = input;

            // Обновляем значение для Livewire
            if (input.length === 11) {
                this.phoneNumber = input;
            } else {
                this.phoneNumber = '';
            }
        },

        onBlur() {
            if (this.displayValue === this.mask) {
                this.displayValue = '';
            }
        }
    }
}
</script>
