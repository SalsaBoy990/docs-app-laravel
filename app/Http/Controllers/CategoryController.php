<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
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
                $this->banner('Nincs feltöltési jogod a kategóriák gyökerébe.', 'danger');
                return redirect()->route('dashboard');
            }
        }
        else if (Gate::denies('authorize_upload_to_category', $category)) {
            $this->banner('Nincs feltöltési jogod a kategóriához.', 'danger');
            return redirect()->route('dashboard');
        }

        $data = $request->all();
        $data['category_id'] = empty($data['category_id']) ? null : intval($data['category_id']);
        $data['user_id'] = auth()->user()->id;

        Category::create($data);

        // categories needs to be re-queried from the db
        session()->forget('categories');

        $this->banner('Új kategória sikeresen hozzáadva.');
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
                $this->banner('Nincs feltöltési jogod a kategóriák gyökerébe.', 'danger');
                return redirect()->route('dashboard');
            }
        }
        if (Gate::denies('authorize_upload_to_category', $category)) {
            $this->banner('Nincs feltöltési jogod a kategóriához.', 'danger');
            return redirect()->route('dashboard');
        }

        $input = $request->all();
//        $input['phonetic_name'] = metaphone($request->input('name'));

        $category->updateOrFail($input);

        // categories needs to be re-queried from the db
        session()->forget('categories');

        $this->banner('A kategóriát sikeresen módosítottad.');
        return redirect()->route('dashboard');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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

        // categories needs to be re-queried from the db
        session()->forget('categories');

        $this->banner('"' . $oldName . '"' . ' sikeresen törölve!');
        return redirect()->route('dashboard');
    }


    /**
     * @param  Category  $category
     * @return RedirectResponse
     */
    public function getSelected(Category $category): RedirectResponse
    {
        $documents = $category->documents()->get();

        return redirect()->route('dashboard')->with([
            'selectedCategory' => $category,
            'documents' => $documents,
        ]);
    }
}
