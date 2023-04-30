<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Support\InteractsWithBanner;

class CreateRoot extends Component
{
    use InteractsWithBanner;

    // used by blade / alpinejs
    public string $title;
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;

    protected array $rules = [
        'name' => 'required|string|min:1|max:255',
    ];

    public function mount(bool $hasSmallButton)
    {
        $this->title = 'Kategória hozzáadása';
        $this->modalId = 'm-new-root';
        $this->hasSmallButton = $hasSmallButton;
    }

    public function __construct()
    {
        $this->title = 'Kategória hozzáadása';
        $this->modalId = 'm-new-root';
        $this->isModalOpen = false;

        $this->name = '';

        parent::__construct();
    }

    public function render()
    {
        return view('livewire.category.create-root');
    }

    public function createCategory()
    {
        // authorize action
        if (Gate::denies('authorize_upload_to_root', User::class)) {
            $this->banner('Nincs feltöltési jogod a kategóriák gyökerébe.', 'danger');
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
                $category['category_id'] = null;
                $category['user_id'] = auth()->user()->id;

                Category::create($category);
            },
            $saveAttempts = 2
        );

        session()->forget('categories');

        $this->banner('Új kategória sikeresen hozzáadva.');
        return redirect()->route('dashboard');
    }
}
