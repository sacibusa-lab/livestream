<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/blog/{slug}', [PageController::class, 'showPost'])->name('post.show');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Admin Fixtures
    Route::get('/fixtures-scores', [\App\Http\Controllers\Admin\FixtureController::class, 'index'])->name('fixtures.scores.index');
    Route::put('/fixtures/{id}/score', [\App\Http\Controllers\Admin\FixtureController::class, 'updateScore'])->name('fixtures.updateScore');

    // Blog Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Fixtures
    Route::post('/fixtures/auto-pull', [App\Http\Controllers\FixtureController::class, 'autoPull'])->name('fixtures.auto-pull');
    Route::post('/fixtures/clear-all', [App\Http\Controllers\FixtureController::class, 'clearAll'])->name('fixtures.clear-all');
    Route::post('/fixtures/{id}/generate-content', [App\Http\Controllers\FixtureController::class, 'generateContent'])->name('fixtures.generate-content');
    Route::get('/fixtures', [App\Http\Controllers\FixtureController::class, 'index'])->name('fixtures.index');
    Route::get('/fixtures/create', [App\Http\Controllers\FixtureController::class, 'create'])->name('fixtures.create');
    Route::post('/fixtures', [App\Http\Controllers\FixtureController::class, 'store'])->name('fixtures.store');
    Route::get('/fixtures/{id}/edit', [App\Http\Controllers\FixtureController::class, 'edit'])->name('fixtures.edit');
    Route::put('/fixtures/{id}', [App\Http\Controllers\FixtureController::class, 'update'])->name('fixtures.update');
    Route::delete('/fixtures/{id}', [App\Http\Controllers\FixtureController::class, 'destroy'])->name('fixtures.destroy');
});

Route::get('/fixtures', [PageController::class, 'fixtures'])->name('fixtures.list');
Route::get('/fixtures/{id}', [PageController::class, 'showFixture'])->name('fixtures.show');
Route::get('/standings', [PageController::class, 'standings'])->name('standings');
