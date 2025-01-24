<div class="flex-1 flex flex-col" x-data="{
    isCreating: false,
    resetForm() {
        $wire.set('newTitle', '');
        $wire.set('newContent', '');
        $wire.set('newDate', '{{ now()->format('d.m.Y') }}');
        $wire.set('newTime', '{{ now()->format('H:i') }}');
    },
    startCreating() {
        this.isCreating = true;
        this.resetForm();
    },
    cancelCreating() {
        this.isCreating = false;
        this.resetForm();
    },
    init() {
        Livewire.on('note-created', () => {
            this.isCreating = false;
        });
    }
}">
    <!-- Заголовок категории и кнопка добавления -->
    <div class="bg-white border-b border-gray-200 p-4 flex justify-between items-center">
        <h2 class="text-xl font-semibold">{{ $selectedCategory ? 'Категория' : 'Все записи' }}</h2>
        <button @click="startCreating()"
                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>добавить</span>
        </button>
    </div>

    <!-- Поиск и фильтры -->
    <div class="bg-white border-b border-gray-200 p-4 grid grid-cols-12 gap-4">
        <div class="col-span-6">
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   placeholder="найти"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
        </div>
        <div class="col-span-3">
            <input wire:model.live.debounce.500ms="date"
                   type="text"
                   placeholder="ДД.ММ.ГГГГ"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
        </div>
        <div class="col-span-3">
            <input wire:model.live.debounce.500ms="time"
                   type="text"
                   placeholder="ЧЧ:ММ"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
        </div>
    </div>

    <!-- Список записей -->
    <div class="flex-1 overflow-y-auto p-4 space-y-6">
        @forelse($notes as $note)
            <livewire:notes.note-item :note="$note" :key="$note->id" />
        @empty
            <div class="text-center text-gray-500">
                Записей не найдено
            </div>
        @endforelse

        <!-- Пагинация -->
        <div class="mt-4">
            {{ $notes->links() }}
        </div>
    </div>

    <!-- Модальное окно создания записи -->
    <template x-teleport="body">
        <div x-show="isCreating"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
             @click="cancelCreating()">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4" @click.stop>
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">Создание записи</h3>
                    <button @click="cancelCreating()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createNote" class="p-6 space-y-4">
                    <div>
                        <input type="text"
                               wire:model="newTitle"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                               placeholder="Заголовок"
                               autofocus>
                        @error('newTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <input type="text"
                                   wire:model="newDate"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-gray-50 cursor-not-allowed"
                                   placeholder="ДД.ММ.ГГГГ"
                                   readonly>
                            @error('newDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <input type="text"
                                   wire:model="newTime"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary bg-gray-50 cursor-not-allowed"
                                   placeholder="ЧЧ:ММ"
                                   readonly>
                            @error('newTime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <select wire:model="newCategoryId"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Без категории</option>
                                @foreach($this->categories as $category)
                                    <option value="{{ $category->id }}"
                                            style="color: {{ $category->color }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <textarea wire:model="newContent"
                                 rows="6"
                                 class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                                 placeholder="Содержание записи"></textarea>
                        @error('newContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button"
                                @click="cancelCreating()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Отмена
                        </button>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 disabled:opacity-50">
                            <span wire:loading.remove>Создать</span>
                            <span wire:loading>Создание...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
