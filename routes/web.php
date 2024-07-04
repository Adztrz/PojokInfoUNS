<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//user
// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/u/{user:username}', [ProfileController::class, 'index'])
    ->name('profile');

Route::get('/g/{group:slug}', [GroupController::class, 'profile'])
    ->name('group.profile');


Route::get('/group/approve-invitation/{token}', [GroupController::class, 'approveInvitation'])->name('group.approveInvitation');

Route::middleware('auth')->group(function () {
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImages');

    //groups
    Route::post('/group', [GroupController::class, 'store'])->name('group.create');

    Route::put('/group/{group:slug}', [GroupController::class, 'update'])->name('group.update');

    Route::post('/group/update-image/{group:slug}', [GroupController::class, 'updateImage'])->name('group.updateImages');

    Route::post('/group/invite/{group:slug}', [GroupController::class, 'inviteUsers'])->name('group.inviteUsers');

    Route::post('/group/join/{group:slug}', [GroupController::class, 'join'])->name('group.join');

    Route::post('/group/approve-request/{group:slug}', [GroupController::class, 'approveRequest'])->name('group.approveRequest');

    Route::delete('/group/remove-user/{group:slug}', [GroupController::class, 'removeUser'])->name('group.removeUser');

    Route::post('/group/change-role/{group:slug}', [GroupController::class, 'changeRole'])->name('group.changeRole');

    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //posts
    Route::get('/post/{post}', [PostController::class, 'view'])->name('post.view');

    Route::post('/post', [PostController::class, 'store'])->name('post.create');

    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');

    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::get('/post/download/{attachment}', [PostController::class, 'downloadAttachment'])->name('post.download');

    Route::post('/post/{post}/reaction', [PostController::class, 'postReaction'])->name('post.reaction');

    Route::get('/search/{search?}', [SearchController::class, 'search'])
        ->name('search');
});

//end

//admin

// Route::group(['prefix' => 'admin', 'middleware' => 'RedirectAdmin'], function () {
//     Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
//     Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
// });

// Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
//     Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
// });

//end
require __DIR__ . '/auth.php';
