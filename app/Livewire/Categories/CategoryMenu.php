<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class CategoryMenu extends Component
{
    public $selectedCategory = null;
    public $isCreating = false;
    public $isEditing = false;
    public $editingCategory = null;

    // Поля для формы
    public $name = '';
    public $color = '#4F46E5';
    public $icon = 'folder';

    protected $rules = [
        'name' => 'required|min:3',
        'color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
        'icon' => 'required'
    ];

    public function selectCategory($categoryId = null)
    {
        $this->selectedCategory = $categoryId;
        $this->dispatch('category-selected', $categoryId);
    }

    public function startCreating()
    {
        $this->isCreating = true;
        $this->resetForm();
    }

    public function startEditing(Category $category)
    {
        $this->isEditing = true;
        $this->editingCategory = $category;
        $this->name = $category->name;
        $this->color = $category->color;
        $this->icon = $category->icon;
    }

    public function cancelForm()
    {
        $this->isCreating = false;
        $this->isEditing = false;
        $this->resetForm();
    }

    public function createCategory()
    {
        $this->validate();

        Category::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->icon
        ]);

        $this->cancelForm();
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Категория создана']);
    }

    public function updateCategory()
    {
        $this->validate();

        $this->editingCategory->update([
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->icon
        ]);

        $this->cancelForm();
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Категория обновлена']);
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Категория удалена']);
    }

    private function resetForm()
    {
        $this->name = '';
        $this->color = '#4F46E5';
        $this->icon = 'folder';
    }

    public function getDefaultCategoriesProperty()
    {
        return Category::query()
            ->where('user_id', auth()->id())
            ->with(['notes' => function($query) {
                $query->latest('date')->limit(3);
            }])
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.categories.category-menu', [
            'categories' => $this->defaultCategories
        ]);
    }
}
