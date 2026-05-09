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

    Route::get('/api/investments', [\App\Http\Controllers\InvestmentController::class, 'index']);
    Route::post('/api/investments', [\App\Http\Controllers\InvestmentController::class, 'store']);
    Route::patch('/api/investments/{id}/price', [\App\Http\Controllers\InvestmentController::class, 'updatePrice']);
    Route::delete('/api/investments/{id}', [\App\Http\Controllers\InvestmentController::class, 'destroy']);

    // Reimbursements
    Route::get('/api/reimbursements', [\App\Http\Controllers\ReimbursementController::class, 'index']);
    Route::post('/api/reimbursements', [\App\Http\Controllers\ReimbursementController::class, 'store']);
    Route::patch('/api/reimbursements/{id}/status', [\App\Http\Controllers\ReimbursementController::class, 'updateStatus']);
    Route::delete('/api/reimbursements/{id}', [\App\Http\Controllers\ReimbursementController::class, 'destroy']);

    // Insurances
    Route::get('/api/insurances', [\App\Http\Controllers\InsuranceController::class, 'index']);
    Route::post('/api/insurances', [\App\Http\Controllers\InsuranceController::class, 'store']);
    Route::delete('/api/insurances/{id}', [\App\Http\Controllers\InsuranceController::class, 'destroy']);

    // Benefits
    Route::get('/api/benefits', [\App\Http\Controllers\BenefitController::class, 'index']);
    Route::post('/api/benefits', [\App\Http\Controllers\BenefitController::class, 'store']);
    Route::delete('/api/benefits/{id}', [\App\Http\Controllers\BenefitController::class, 'destroy']);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'verifyPin']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

