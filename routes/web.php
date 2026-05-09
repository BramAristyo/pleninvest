<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return '<h1>This is register page</h1>';
});
