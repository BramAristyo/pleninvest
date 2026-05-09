<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.auth');
})->name('auth');

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

Route::get('/transactions', function () {
    return view('pages.transactions');
})->name('transactions');

Route::get('/diversification', function () {
    return view('pages.diversification');
})->name('diversification');

Route::get('/reimbursement', function () {
    return view('pages.reimbursement');
})->name('reimbursement');

Route::get('/insurance', function () {
    return view('pages.insurance');
})->name('insurance');

Route::get('/charts', function () {
    return view('pages.charts');
})->name('charts');

Route::get('/health', function () {
    return view('pages.health');
})->name('health');

Route::get('/tips', function () {
    return view('pages.tips');
})->name('tips');

Route::get('/sheets', function () {
    return view('pages.sheets');
})->name('sheets');
