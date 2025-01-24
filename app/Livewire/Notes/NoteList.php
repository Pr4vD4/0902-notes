<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class NoteList extends Component
{
    use WithPagination;

    public $search = '';
    public $date = '';
    public $time = '';
    public $selectedCategory = null;

    // Поля для новой записи
    public $isCreating = false;
    public $newTitle = '';
    public $newContent = '';
    public $newDate = '';
    public $newTime = '';
    public $newCategoryId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'date' => ['except' => ''],
        'time' => ['except' => '']
    ];

    protected $listeners = [
        'note-updated' => '$refresh',
        'note-deleted' => '$refresh',
        'category-selected' => 'setCategory'
    ];

    public function mount()
    {
        $this->newDate = now()->format('d.m.Y');
        $this->newTime = now()->format('H:i');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function formatTime($value)
    {
        // Убираем все нецифровые символы
        $digits = preg_replace('/\D/', '', $value);

        if (empty($digits)) {
            return '';
        }

        // Если введено только 1 или 2 цифры (часы)
        if (strlen($digits) <= 2) {
            $hours = str_pad($digits, 2, '0', STR_PAD_LEFT);
            if ($hours > 23) $hours = '23';
            return $hours; // Возвращаем только часы без минут
        }

        // Если введено больше цифр, форматируем часы и минуты
        $hours = substr($digits, 0, 2);
        $minutes = substr($digits . '00', 2, 2);

        if ($hours > 23) $hours = '23';
        if ($minutes > 59) $minutes = '59';

        return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    protected function isOnlyHours($time)
    {
        return strlen($time) === 2 && is_numeric($time);
    }

    protected function formatDate($value)
    {
        // Убираем все нецифровые символы
        $digits = preg_replace('/\D/', '', $value);

        if (empty($digits)) {
            return '';
        }

        // Добавляем точки между числами
        $parts = [
            substr($digits . '00', 0, 2),
            substr($digits . '00', 2, 2),
            substr($digits . '2024', 4, 4)
        ];

        // Валидация дня и месяца
        if ($parts[0] > 31) $parts[0] = '31';
        if ($parts[1] > 12) $parts[1] = '12';

        return implode('.', $parts);
    }

    public function getNotesProperty()
    {
        return Note::query()
            ->where('user_id', auth()->id())
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->date, function ($query) {
                try {
                    $date = Carbon::createFromFormat('d.m.Y', $this->date)->startOfDay();
                    $query->whereDate('date', $date);
                } catch (\Exception $e) {
                    // Игнорируем некорректную дату
                }
            })
            ->when($this->time, function ($query) {
                try {
                    if ($this->isOnlyHours($this->time)) {
                        // Если указаны только часы, ищем в диапазоне часа
                        $startTime = Carbon::createFromFormat('H:i', $this->time . ':00');
                        $endTime = Carbon::createFromFormat('H:i', $this->time . ':59');

                        $query->whereTime('date', '>=', $startTime->format('H:i:s'))
                              ->whereTime('date', '<=', $endTime->format('H:i:s'));
                    } else {
                        // Если указано точное время
                        $time = Carbon::createFromFormat('H:i', $this->time);
                        $query->whereTime('date', $time->format('H:i:s'));
                    }
                } catch (\Exception $e) {
                    // Игнорируем некорректное время
                }
            })
            ->orderBy('date', 'desc')
            ->paginate(10);
    }

    public function updatedTime($value)
    {
        $this->time = $this->formatTime($value);
    }

    public function updatedDate($value)
    {
        $this->date = $this->formatDate($value);
    }

    public function getCategoriesProperty()
    {
        return Category::query()
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
    }

    public function createNote()
    {
        $this->validate([
            'newTitle' => 'required|min:3',
            'newContent' => 'required',
            'newDate' => 'required|date_format:d.m.Y',
            'newTime' => 'required|date_format:H:i',
        ], [], [
            'newTitle' => 'заголовок',
            'newContent' => 'содержание',
            'newDate' => 'дата',
            'newTime' => 'время',
        ]);

        try {
            $datetime = Carbon::createFromFormat('d.m.Y H:i', $this->newDate . ' ' . $this->newTime);

            Note::create([
                'user_id' => auth()->id(),
                'category_id' => $this->newCategoryId,
                'title' => $this->newTitle,
                'content' => $this->newContent,
                'date' => $datetime,
            ]);

            $this->reset(['newTitle', 'newContent', 'newDate', 'newTime', 'newCategoryId']);
            $this->dispatch('note-created');
            $this->dispatch('showToast', ['type' => 'success', 'message' => 'Запись успешно создана']);
        } catch (\Exception $e) {
            Log::error('Ошибка при создании записи: ' . $e->getMessage(), [
                'data' => [
                    'title' => $this->newTitle,
                    'content' => $this->newContent,
                    'date' => $this->newDate,
                    'time' => $this->newTime,
                    'category_id' => $this->newCategoryId,
                    'user_id' => auth()->id()
                ]
            ]);
            $this->dispatch('showToast', ['type' => 'error', 'message' => 'Ошибка при создании записи']);
        }
    }

    public function startCreating()
    {
        $this->isCreating = true;
        $this->newDate = now()->format('d.m.Y');
        $this->newTime = now()->format('H:i');
    }

    public function cancelCreating()
    {
        $this->reset(['newTitle', 'newContent', 'newDate', 'newTime', 'isCreating']);
    }

    public function setCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function render()
    {
        return view('livewire.notes.note-list', [
            'notes' => $this->notes
        ]);
    }
}
