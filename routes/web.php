<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFriendController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'IndexController@index');

Route::middleware(['auth'])
    ->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        Route::get('/user/info', [UserController::class, 'info'])->name('user.info');
        Route::get('/user/friends', [UserController::class, 'friends'])->name('user.friends');
        Route::get('/user/friends/{friend}/msgs', [UserController::class, 'getFriendsMsgs'])->name('user.friends.msgs');

        Route::get('/user/recent-contacts', [UserController::class, 'getRecentContacts'])->name('user.recent-contacts');
        Route::post('/user/recent-contacts', [UserController::class, 'storeRecentContacts']);

        Route::post('/user-friends', [UserFriendController::class, 'store'])->name('user-friend.store');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    });

