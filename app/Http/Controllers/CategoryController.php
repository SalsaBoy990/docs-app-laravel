<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Throwable;

class CategoryController extends Controller
{
    use InteractsWithBanner;


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCategoryRequest  $request
     * @param  Category  $category
     * @return RedirectResponse
     */
    public function store(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        if (!$category->id) {
            if (Gate::denies('authorize_upload_to_root', User::class)) {
                $this->banner(__('You have no upload rights to the root level of the categories.'), 'danger');
                return redirect()->route('dashboard');
            }
        } else {
            if (Gate::denies('authorize_upload_to_category', $category)) {
                $this->banner(__('You have no upload rights to this category.'), 'danger');
                return redirect()->route('dashboard');
            }
        }

        $data = $request->all();
        $data['category_id'] = empty($data['category_id']) ? null : intval($data['category_id']);
        $data['user_id'] = auth()->user()->id;

        Category::create($data);

        $this->banner(__('New category added.'));
        return redirect()->route('dashboard');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCategoryRequest  $request
     * @param  Category  $category
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        if (!$category->id) {
            if (Gate::denies('authorize_upload_to_root', User::class)) {
                $this->banner(__('You have no upload rights to the root level of the categories.'), 'danger');
                return redirect()->route('dashboard');
            }
        }
        if (Gate::denies('authorize_upload_to_category', $category)) {
            $this->banner(__('You have no upload rights to this category.'), 'danger');
            return redirect()->route('dashboard');
        }

        $input = $request->all();
        $category->updateOrFail($input);

        $this->banner(__('Successfully updated the category.'));
        return redirect()->route('dashboard');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category  $category
     *
     * @return Application|Factory|View
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit')->with([
            'category' => $category
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Category $category): RedirectResponse
    {
        $oldName = htmlentities($category->name);
        $category->deleteOrFail();

        $this->banner(__('":NAME" successfully deleted!', ['NAME' => $oldName]));
        return redirect()->route('dashboard');
    }


    /**
     * @param  Category  $category
     *
     * @return Factory|View|Application
     */
    public function getSelected(Category $category): Factory|View|Application
    {
        $categories = Category::whereNull('category_id')
            ->with(['categories', 'users'])
            ->orderBy('name', 'ASC')
            ->get();

        $documents = $category->documents()->get();

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
            'selectedCategory' => $category,
            'searchResults' => $searchResults ?? [],
            'parentCategories' => array_reverse($parentCategories, true),
        ]);

    }
}
