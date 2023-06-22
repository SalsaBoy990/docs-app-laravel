<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class PostController extends Controller
{
    use InteractsWithBanner;


    /**
     * Show the form for creating a new resource.
     *
     * @param $categoryId
     *
     * @return Application|Factory|View
     */
    public function create($categoryId): View|Factory|Application
    {
        return view('admin.post.create')->with([
            'categoryId' => intval($categoryId),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePostRequest  $request
     * @param $categoryId
     *
     * @return RedirectResponse
     */
    public function store(StorePostRequest $request, $categoryId): RedirectResponse
    {
        $data = $request->all();

        $newPost = Post::create($data);
        $newPost->save();

        $catId = intval($categoryId);

        $newPost->categories()->attach($catId);

        $this->banner('New post is added.');
        return redirect()->route('category.selected', $catId);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post  $post
     *
     * @return Application|Factory|View
     */
    public function edit(Post $post, $categoryId): View|Factory|Application
    {
        return view('admin.post.edit')->with([
            'post' => $post,
            'categoryId' => intval($categoryId)
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePostRequest  $request
     * @param  Post  $post
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdatePostRequest $request, Post $post, $categoryId): RedirectResponse
    {
        $input = $request->all();
        $post->updateOrFail($input);

        $catId = intval($categoryId);

        $this->banner('Successfully updated the post');
        return redirect()->route('category.selected', $catId);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Post $post): RedirectResponse
    {
        $oldTitle = htmlentities($post->title);
        $post->deleteOrFail();

        $this->banner('"'.$oldTitle.'"'.' successfully deleted!');
        return redirect()->route('category.selected');
    }
}
