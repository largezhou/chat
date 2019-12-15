<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'IndexController@index');

Route::prefix('/user')
    ->group(function () {
        Route::middleware(['auth'])
            ->group(function () {
                Route::get('/info', 'UserController@info')->name('user.info');
                Route::get('/friends', 'UserController@friends')->name('user.friends');
            });
    });

