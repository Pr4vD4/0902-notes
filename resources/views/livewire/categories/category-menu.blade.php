<div class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold">Категории</h2>
        <button wire:click="startCreating" class="text-gray-400 hover:text-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-4">
        <!-- Все записи -->
        <div>
            <button wire:click="selectCategory()"
                    class="w-full text-left px-4 py-2 rounded-lg {{ is_null($selectedCategory) ? 'bg-primary/10 text-primary' : 'hover:bg-gray-100' }}">
                Все записи
            </button>
        </div>

        <!-- Список категорий -->
        @foreach($categories as $category)
            <div class="space-y-2">
                <div class="group flex items-center justify-between">
                    <button wire:click="selectCategory({{ $category->id }})"
                            class="flex-1 text-left px-4 py-2 rounded-lg {{ $selectedCategory == $category->id ? 'bg-primary/10 text-primary' : 'hover:bg-gray-100' }}">
                        <span class="inline-flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $category->color }}"></span>
                            <span>{{ $category->name }}</span>
                        </span>
                    </button>
                    <div class="hidden group-hover:flex items-center space-x-1 px-2">
                        <button wire:click="startEditing({{ $category->id }})" class="text-gray-400 hover:text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </button>
                        <button wire:click="deleteCategory({{ $category->id }})" class="text-gray-400 hover:text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Последние записи категории -->
                @if($category->notes->isNotEmpty())
                    <div class="ml-6 space-y-1">
                        @foreach($category->notes as $note)
                            <button @click="$dispatch('show-note', { id: {{ $note->id }} })"
                                    class="w-full text-left group">
                                <div class="text-sm text-gray-600 group-hover:text-gray-900">
                                    <p class="truncate">{{ $note->title }}</p>
                                    <p class="text-xs text-gray-400">{{ $note->date->format('d.m.Y H:i') }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Модальное окно создания/редактирования категории -->
    @if($isCreating || $isEditing)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ $isEditing ? 'Редактирование' : 'Создание' }} категории</h3>
                    <button wire:click="cancelForm" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="{{ $isEditing ? 'updateCategory' : 'createCategory' }}" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Название</label>
                        <input type="text"
                               wire:model="name"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Цвет</label>
                        <input type="color"
                               wire:model="color"
                               class="mt-1 w-full h-10 p-1 border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                        @error('color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button"
                                wire:click="cancelForm"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                            {{ $isEditing ? 'Сохранить' : 'Создать' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
