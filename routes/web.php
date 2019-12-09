<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'IndexController@index');

Route::get('/users/info', 'UserController@info')
    ->middleware(['auth'])
    ->name('user.info');
