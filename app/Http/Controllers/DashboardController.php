<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class DashboardController extends Controller
{
    use InteractsWithBanner;


    /**
     * Admin dashboard page
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $categories = Category::whereNull('category_id')
            ->with(['categories', 'users', 'posts'])
            ->orderBy('name', 'ASC')
            ->get();

        // the default is the first category
        $selectedCategory = $categories->first();
        $documents = $selectedCategory->documents()->get();

        $parentCategories = [];
        $parentCategoryId = $selectedCategory->category_id ?? null;

        while ($parentCategoryId !== null) {
            $currentCategory = Category::where('id', $parentCategoryId)->first();
            $parentCategories[$currentCategory->id] = $currentCategory->name;
            $parentCategoryId = $currentCategory->category_id ?? null;
        }

        return view('admin.dashboard')->with([
            'categories' => $categories,
            'documents' => $documents,
            'selectedCategory' => $selectedCategory,
            'searchResults' => $searchResults ?? [],
            'parentCategories' => array_reverse($parentCategories, true),
        ]);
    }
}
