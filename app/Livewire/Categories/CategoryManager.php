<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CategoryManager extends Component
{
    public $categories;
    public $name, $type = 'expense', $icon, $color = '#3B82F6';
    public $categoryId = null;
    public $isModalOpen = false;

    public $icons = [
        'currency-dollar', 'gift', 'chart-bar', 'shopping-cart', 'truck',
        'document-text', 'shopping-bag', 'heart', 'academic-cap', 'film',
        'sparkles', 'home', 'lightning-bolt', 'briefcase', 'book-open'
    ];

    public function render()
    {
        $this->categories = Category::where('user_id', Auth::id())
            ->orWhere('is_default', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('livewire.categories.category-manager')->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->type = 'expense';
        $this->icon = '';
        $this->color = '#3B82F6';
        $this->categoryId = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'type' => 'required|in:income,expense',
            'icon' => 'required',
            'color' => 'required',
        ]);

        Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
            'type' => $this->type,
            'icon' => $this->icon,
            'color' => $this->color,
            'user_id' => Auth::id(),
            'is_default' => false,
        ]);

        session()->flash('message',
            $this->categoryId ? 'Kategori berhasil diupdate.' : 'Kategori berhasil ditambahkan.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        if($category->is_default) {
            session()->flash('error', 'Kategori default tidak dapat diubah.');
            return;
        }

        $this->categoryId = $id;
        $this->name = $category->name;
        $this->type = $category->type;
        $this->icon = $category->icon;
        $this->color = $category->color;

        $this->openModal();
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        
        if($category->is_default) {
            session()->flash('error', 'Kategori default tidak dapat dihapus.');
            return;
        }

        $category->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }
}
