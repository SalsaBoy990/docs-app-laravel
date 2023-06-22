<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Frontend\PostController as PostFrontendController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/* Get language, set language in session */
Route::get('lang/{locale}', [LocalizationController::class, 'index'])->name('lang.index');


// Routes only for authenticated users...
Route::group(
    ['middleware' => ['auth', 'verified'], 'prefix' => 'admin'],
    function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


        /* Posts */
        Route::post('post/{category}', [PostController::class, 'store'])->name('post.store');
        Route::get(
            'post/create/{category}', [PostController::class, 'create'])->name('post.create');

        Route::get(
            'post/{post}/category/{category}', [PostController::class, 'edit'])->name('post.edit');

        Route::put('post/{post}', [PostController::class, 'update'])->name('post.update');
        Route::delete('post/{post}', [PostController::class, 'destroy'])->name('post.destroy');


        /* Categories */
        Route::post('category', [CategoryController::class, 'store'])->name('category.store');
        Route::get(
            'category/{category}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('category/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
        Route::get('category/selected/{category}', [CategoryController::class, 'getSelected'])->name('category.selected');


        /* Documents */
        Route::post('document/{category}/create', [DocumentController::class, 'store'])->name('document.store');
        Route::put('document/{document}', [DocumentController::class, 'update'])->name('document.update');
        Route::delete('document/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');


        /* Set permissions */
        Route::get('/category/user/{user}/root', [CategoryUserController::class, 'toggleCategoryRootUploadPermission'])->name('permission.root.upload.toggle');
        Route::get('/category/{category}/user/{user}', [CategoryUserController::class, 'getPermission'])->name('permission.get');
        Route::get('/category/{category}/user/{user}/download', [CategoryUserController::class, 'attachDownloadPermission'])->name('permission.download.attach');
        Route::get('/category/{category}/user/{user}/upload', [CategoryUserController::class, 'attachUploadPermission'])->name('permission.upload.attach');
        Route::get('/category/{category}/user/{user}/download/detach', [CategoryUserController::class, 'detachDownloadPermission'])->name('permission.download.detach');
        Route::get('/category/{category}/user/{user}/upload/detach', [CategoryUserController::class, 'detachUploadPermission'])->name('permission.upload.detach');
    }
);


// Routes only for authenticated users...
Route::group(
    ['middleware' => [], 'prefix' => ''],
    function () {
        /* Posts */
        Route::get('posts', [PostFrontendController::class, 'index'])->name('post.frontend.index');
        Route::get( 'post/{slug}', [PostFrontendController::class, 'show'])->name('post.frontend.show');
        Route::get( 'post/category/{name}', [PostFrontendController::class, 'category'])->name('post.frontend.category');
    }
);
