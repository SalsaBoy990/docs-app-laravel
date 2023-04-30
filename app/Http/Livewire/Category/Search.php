<?php

namespace App\Http\Livewire\Category;

use App\Models\Category;
use App\Support\InteractsWithBanner;
use Livewire\Component;

class Search extends Component
{
    use InteractsWithBanner;

    public string $searchTerm;
    public bool $panelOpen;
    public $results;
    public int $count;

    public function mount()
    {
        $this->searchTerm = '';
        $this->panelOpen = false;
        $this->results = null;
        $this->count = 0;

    }

    public function searchCategory() {

//        $phoneticSearchTerm = metaphone($this->searchTerm);
        $results = Category::whereFullText(['name', 'content'], $this->searchTerm)->get();

        $this->panelOpen = true;
        $this->count = count($results);
        $this->results = $results;

        session()->forget('searchResults');
        session(['searchResults' => $results]);
    }

    public function render()
    {
        return view('livewire.category.search');
    }
}
