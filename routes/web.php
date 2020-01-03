<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'IndexController@index');

Route::middleware(['auth'])
    ->group(function () {
        Route::get('/user/info', 'UserController@info')->name('user.info');
        Route::get('/user/friends', 'UserController@friends')->name('user.friends');
        Route::get('/user/friends/{friend}/msgs', 'UserController@getFriendsMsgs')->name('user.friends.msgs');
    });

