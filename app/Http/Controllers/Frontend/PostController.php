<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class PostController extends Controller
{
    use InteractsWithBanner;


    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $posts = Post::with('categories')
            ->orderByDesc('created_at')
            ->paginate(2);

        return view('public.post.index')->with([
            'posts' => $posts,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $slug
     *
     * @return Application|Factory|View
     */
    public function show(string $slug): View|Factory|Application
    {
        $post = Post::where('slug', '=', strip_tags($slug))
            ->with('categories')
            ->first();


        return view('public.post.show')->with([
            'post' => $post,
        ]);
    }


    /**
     * @param  string  $name
     *
     * @return View|Factory|Application
     */
    public function category(string $name): View|Factory|Application
    {

        $category = Category::where('name', '=', strip_tags($name))
            ->with('posts')
            ->first();

        $posts = $category->posts()->paginate(1);

        return view('public.post.category')->with([
            'category' => $category,
            'posts' => $posts,
        ]);

    }


}
