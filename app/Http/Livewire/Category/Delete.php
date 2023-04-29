<?php

namespace App\Http\Livewire\Category;

use App\Models\Category;
use App\Support\InteractsWithBanner;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    use InteractsWithBanner;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public int $categoryId;
    public string $name;
    public $category;

    protected array $rules = [
        'categoryId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, $category, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->category = $category;
        $this->categoryId = $category->id;
        $this->name = $category->name;
    }


    public function render()
    {
        return view('livewire.category.delete');
    }

    public function deleteCategory()
    {
        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () {
                $category = Category::findOrFail($this->categoryId);
                $category->delete();
            },
            $deleteAttempts = 2
        );
        session()->forget('categories');
        // session()->forget('selectedCategory');

        $this->banner('A kategória törlése sikeres volt.');
        return redirect()->route('dashboard');
    }
}
