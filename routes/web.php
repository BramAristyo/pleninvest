<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('app');
    })->name('app.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Transactions
    Route::get('/api/transactions', [\App\Http\Controllers\TransactionController::class, 'index']);
    Route::post('/api/transactions', [\App\Http\Controllers\TransactionController::class, 'store']);
    Route::post('/api/transactions/batch', [\App\Http\Controllers\TransactionController::class, 'storeBatch']);
    Route::delete('/api/transactions/{id}', [\App\Http\Controllers\TransactionController::class, 'destroy']);

    // Net Worth
    Route::get('/api/net-worth', [\App\Http\Controllers\NetWorthController::class, 'show']);
    Route::post('/api/net-worth', [\App\Http\Controllers\NetWorthController::class, 'update']);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'verifyPin']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

