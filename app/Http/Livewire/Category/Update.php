<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Support\InteractsWithBanner;

class Update extends Component
{
    use InteractsWithBanner, AuthorizesRequests;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public int $categoryId;
    public $category;

    protected array $rules = [
        'name' => 'required|string|min:1|max:255',
        'categoryId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, $category, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->category = $category;
        $this->name = $category->name;
        $this->categoryId = $category->id;
    }


    public function render()
    {
        return view('livewire.category.update');
    }

    public function updateCategory()
    {
        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () {
                $category = Category::findOrFail($this->categoryId);
                $category->update([
                    'name' => $this->name
                ]);
            },
            $updateAttempts = 2
        );
        session()->forget('categories');
        session()->forget('selectedCategory');
        session(['selectedCategory' => $this->category]);

        $this->banner('Kategória sikeresen frissítve.');
        return redirect()->route('dashboard')->with( [ 'selectedCategory' => $this->category]);
    }
}
