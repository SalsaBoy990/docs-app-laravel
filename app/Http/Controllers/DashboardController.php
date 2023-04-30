<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;


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
        if (!session()->exists('categories')) {
            $categories = Category::whereNull('category_id')
                ->with(['categories', 'users'])
                ->get();

            // The categories are stored in the session to have fewer queries
            // Only when creating, updating and deleting categories is the session deleted,
            // and the queries then re-run
            session(['categories' => $categories]);
        } else {
            $categories = Session::get('categories');
        }

        // If exists in session, then use it
        $documents = Session::get('documents');
        $selectedCategory = Session::get('selectedCategory');
        $searchResults = Session::get('searchResults');

        if (!$documents || !$selectedCategory) {
            // the default is the first category
            $selectedCategory = $categories->first();
            $documents = $selectedCategory->documents()->get();
        }

        $parentCategories = [];
        $parentCategoryId = $selectedCategory->category_id ?? null;
        while($parentCategoryId !== null) {
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
