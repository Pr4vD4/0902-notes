<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use App\Models\Category;
use Livewire\Component;
use Carbon\Carbon;

class NoteItem extends Component
{
    protected $listeners = [
        'openNote' => 'openNote'
    ];

    public Note $note;
    public $noteId;
    public $isEditing = false;
    public $isViewing = false;
    public $title;
    public $content;
    public $date;
    public $time;
    public $categories = [];
    public $selectedCategoryId;

    public function mount(Note $note)
    {
        $this->note = $note;
        $this->noteId = $note->id;
        $this->resetForm();
        $this->categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
        $this->selectedCategoryId = $note->category_id;
    }

    public function resetForm()
    {
        $this->title = $this->note->title;
        $this->content = $this->note->content;
        $this->date = $this->note->date->format('d.m.Y');
        $this->time = $this->note->date->format('H:i');
    }

    public function viewNote()
    {
        $this->isViewing = true;
    }

    public function closeView()
    {
        $this->isViewing = false;
    }

    public function startEdit()
    {
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->resetForm();
    }

    public function deleteNote()
    {
        $this->dispatch('confirmDelete');
    }

    public function confirmDelete()
    {
        $this->note->delete();
        $this->dispatch('note-deleted');
        $this->dispatch('closeModals');
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Запись успешно удалена']);
    }

    public function saveNote()
    {
        $this->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'date' => 'required|date_format:d.m.Y',
            'time' => 'required|date_format:H:i',
        ]);

        $datetime = Carbon::createFromFormat('d.m.Y H:i', $this->date . ' ' . $this->time);

        $this->note->update([
            'title' => $this->title,
            'content' => $this->content,
            'date' => $datetime,
            'category_id' => $this->selectedCategoryId
        ]);

        $this->dispatch('note-updated');
        $this->dispatch('closeModals');
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Запись успешно обновлена']);
    }

    public function openNote($data)
    {
        if ($data['noteId'] === $this->note->id) {
            $this->isViewing = true;
        }
    }

    public function render()
    {
        return view('livewire.notes.note-item');
    }
}
