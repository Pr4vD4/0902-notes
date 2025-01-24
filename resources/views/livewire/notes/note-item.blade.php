<div x-data="{
    isViewing: false,
    isEditing: false,
    init() {
        Livewire.on('closeModals', () => {
            this.isViewing = false;
            this.isEditing = false;
        });

        // Обработка подтверждения удаления
        Livewire.on('confirmDelete', () => {
            Swal.fire({
                title: 'Удалить запись?',
                text: 'Это действие нельзя будет отменить',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Удалить',
                cancelButtonText: 'Отмена'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.confirmDelete();
                }
            });
        });

        // Добавляем обработчик события show-note
        window.addEventListener('show-note', (event) => {
            if (event.detail.id === {{ $noteId }}) {
                this.isViewing = true;
            }
        });
    }
}">
    <!-- Карточка записи -->
    <div class="bg-white rounded-lg shadow p-6 cursor-pointer" @click="isViewing = true">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-semibold">{{ $note->title }}</h3>
                <div class="text-sm text-gray-500 flex items-center space-x-2">
                    <span>{{ $note->date->format('H:i') }}</span>
                    <span>{{ $note->date->format('d.m.Y') }}</span>
                    @if($note->category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                              style="background-color: {{ $note->category->color }}20; color: {{ $note->category->color }}">
                            {{ $note->category->name }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-2">
                <button @click.stop="isEditing = true" class="text-gray-400 hover:text-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>
                <button @click.stop="$wire.deleteNote()" class="text-gray-400 hover:text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
        <p class="text-gray-600 line-clamp-3">{{ $note->content }}</p>
    </div>

    <!-- Модальное окно просмотра -->
    <template x-teleport="body">
        <div x-show="isViewing"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
             @click="isViewing = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 overflow-hidden" @click.stop>
                <!-- Шапка модального окна -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">{{ $note->title }}</h3>
                    <button @click="isViewing = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Тело модального окна -->
                <div class="px-6 py-4">
                    <div class="mb-4 flex items-center space-x-2 text-sm text-gray-500">
                        <span>{{ $note->date->format('H:i') }}</span>
                        <span>{{ $note->date->format('d.m.Y') }}</span>
                        @if($note->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                  style="background-color: {{ $note->category->color }}20; color: {{ $note->category->color }}">
                                {{ $note->category->name }}
                            </span>
                        @endif
                    </div>
                    <div class="prose max-w-none">
                        {{ $note->content }}
                    </div>
                </div>

                <!-- Футер модального окна -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-2">
                    <button @click="isViewing = false; isEditing = true"
                            class="px-4 py-2 text-primary hover:text-primary/90 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        <span>Редактировать</span>
                    </button>
                    <button @click="$wire.deleteNote()"
                            class="px-4 py-2 text-red-600 hover:text-red-700 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Удалить</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Форма редактирования -->
    <template x-teleport="body">
        <div x-show="isEditing"
             x-transition.opacity
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-900">Редактирование записи</h3>
                    <button @click="isEditing = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveNote" class="p-6 space-y-4">
                    <div>
                        <input type="text"
                               wire:model="title"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                               placeholder="Заголовок">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <input type="text"
                                   wire:model="date"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                                   placeholder="ДД.ММ.ГГГГ">
                            @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <input type="text"
                                   wire:model="time"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                                   placeholder="ЧЧ:ММ">
                            @error('time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <select wire:model="selectedCategoryId"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Без категории</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            style="color: {{ $category->color }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <textarea wire:model="content"
                                 rows="6"
                                 class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                                 placeholder="Содержание записи"></textarea>
                        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button"
                                @click="isEditing = false"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
