<?php

use App\Http\Controllers\Auth\SecretaryAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NeedController;
use Illuminate\Support\Facades\Route;

// Page d'accueil redirige vers le login
Route::get('/', function () {
    return redirect()->route('secretary.login');
});

// Authentification des secrétaires
Route::get('/login', [SecretaryAuthController::class, 'showLoginForm'])->name('secretary.login');
Route::post('/login', [SecretaryAuthController::class, 'login']);
Route::post('/logout', [SecretaryAuthController::class, 'logout'])->name('secretary.logout');

// Routes protégées
Route::middleware([\App\Http\Middleware\SecretaryAuth::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Étudiants
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    
    // Paiements
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create/{registration}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/store/{registration}', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/receipt/{payment}', [PaymentController::class, 'generateReceipt'])->name('payments.receipt');
    
    // Besoins
    Route::get('/needs', [NeedController::class, 'index'])->name('needs.index');
    Route::get('/needs/create', [NeedController::class, 'create'])->name('needs.create');
    Route::post('/needs', [NeedController::class, 'store'])->name('needs.store');
    Route::post('/needs/{need}/status', [NeedController::class, 'updateStatus'])->name('needs.updateStatus');
});