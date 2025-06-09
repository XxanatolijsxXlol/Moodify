<?php
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\NotificationController;

use Illuminate\Support\Facades\Route;

Route::get('/', [PostsController::class, 'index'])->name('welcome');
Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profiles.show');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->name('api.search');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/posts/{post}/comments', [PostsController::class, 'storeComment'])->middleware('auth');
    Route::get('/posts/{post}/comments', [PostsController::class, 'getComments'])->middleware('auth');
    Route::post('/posts/{post}/like', [PostsController::class, 'like'])->middleware('auth');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/p/create', [PostsController::class, 'create']);
    

    Route::post('/p', [PostsController::class, 'store']);
    Route::get('/p/{post}', [PostsController::class, 'show']);
    Route::delete('/p/{post}', [PostsController::class, 'destroy']);

    // Moved follow routes here
    Route::post('/users/follow/{user}', [FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/unfollow/{user}', [FollowController::class, 'destroy'])->name('users.unfollow');
    Route::get('/profiles/{user}/followers-list', [ProfilesController::class, 'getFollowersList'])->name('profiles.followers_list');
Route::get('/profiles/{user}/following-list', [ProfilesController::class, 'getFollowingList'])->name('profiles.following_list');
});

Route::middleware(['auth'])->group(function () {
  
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/start', [MessageController::class, 'start'])->name('messages.start');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');

    // New routes for message status updates
    Route::post('/messages/{message}/delivered', [MessageController::class, 'markAsDelivered']);
    Route::post('/conversations/{conversation}/mark-as-read', [MessageController::class, 'markAsRead']);
        Route::post('/conversations/{conversation}/mark-messages-as-read', [MessageController::class, 'markSpecificMessagesAsRead']);

});
Route::middleware('auth')->group(function () {
Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth')->name('notifications.data');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::middleware('auth')->group(function () {
Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
Route::get('themes/create', [ThemeController::class, 'create'])->name('themes.create');
Route::post('themes', [ThemeController::class, 'store'])->name('themes.store');
Route::post('themes/{theme}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
Route::delete('themes/{theme}', [ThemeController::class, 'destroy'])->name('themes.destroy');
Route::post('/themes/activate/default', [ThemeController::class, 'activateDefault'])->name('themes.activateDefault');

});
require __DIR__ . '/auth.php';