<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Support\InteractsWithBanner;

class Create extends Component
{
    use InteractsWithBanner;

    // used by blade / alpinejs
    public $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public $category;
    public int $categoryId;

    protected array $rules = [
        'name' => 'required|string|min:1|max:255',
        'categoryId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, $category, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->name = '';
        $this->category = $category;
        $this->categoryId = $category->id;
    }


    public function render()
    {
        return view('livewire.category.create');
    }

    public function createCategory()
    {
        // authorize action
        if (Gate::denies('authorize_upload_to_category', $this->category)) {
            $this->banner('Nincs feltöltési jogod ehhez a kategóriához.', 'danger');
            return redirect()->route('dashboard');
        }

        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () {
                $category = [];
                $category['name'] = $this->name;
//                $category['phonetic_name'] = metaphone($this->name);
                $category['category_id'] = $this->categoryId;
                $category['user_id'] = auth()->user()->id;

                Category::create($category);
            },
            $saveAttempts = 2
        );

        session()->forget('categories');
        // session()->forget('selectedCategory');

        $this->banner('Új alkategória sikeresen hozzáadva.');
        return redirect()->route('dashboard');
    }
}
