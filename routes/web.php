<?php


use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NiceController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PostController::class, 'welcome'])
    ->name('root');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('posts', PostController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

Route::resource('posts', PostController::class)
    ->only(['show', 'index']);

Route::get('/posts/nice/{post}', [NiceController::class, 'nice'])
    ->name('nice');
Route::get('/posts/unnice/{post}', [NiceController::class, 'unnice'])
    ->name('unnice');
Route::get('/search', [PostController::class, 'search'])
    ->name('search');
Route::get('/posts/profile/{user_id}', [PostController::class, 'profile'])
    ->name('posts.profile');
Route::get('/bookmark', [PostController::class, 'bookmark'])
    ->name('bookmark')
    ->middleware('auth');


require __DIR__.'/auth.php';
