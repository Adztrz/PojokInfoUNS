<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
    return redirect(route('home'));
});

// Temporary fix for unknown bug.
Route::get('/favicon.ico', function () {
    return redirect(route('home'));
});

// Test logging route
Route::get('/test-log', function () {
    Log::channel('user_actions')->info('This is a test log message');
    return 'Log test completed';
});

// Authenticated routes
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::resource('/posts', PostController::class)->names('posts');
    Route::get('/feeds', [PostController::class, 'followers'])->name('feeds');
    Route::resource('/manage/users', UserController::class)->except(['create', 'show', 'store'])->names('users');
    Route::get('/{username}', [ProfileController::class, 'show'])->name('profile');
    Route::delete('/manage/users', [UserController::class, 'destroy'])->name('destroy');
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
});
