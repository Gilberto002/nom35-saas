<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Inertia\Inertia;
use App\Http\Controllers\BillingController;

// ══════════════════════════════════════════════════════════════
// PÚBLICAS
// ══════════════════════════════════════════════════════════════

Route::get('/', function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return view('welcome');
})->name('home');

Route::get('/login',    fn() => Auth::check() ? redirect()->route('dashboard') : Inertia::render('Auth/Login'))->name('login');
Route::get('/register', fn() => Auth::check() ? redirect()->route('dashboard') : Inertia::render('Register'))->name('register');

Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ══════════════════════════════════════════════════════════════
// PROTEGIDAS
// ══════════════════════════════════════════════════════════════

Route::middleware(['auth:sanctum', 'company.active'])->group(function () {
    Route::get('/dashboard',         [DashboardController::class, 'panel'])->name('dashboard');
    Route::get('/employees',         fn() => Inertia::render('Employees'))->name('employees');
    Route::get('/areas',             fn() => Inertia::render('Areas'))->name('areas');
    Route::get('/results',           fn() => Inertia::render('Results'))->name('results');
    Route::get('/reports',           fn() => Inertia::render('Reports'))->name('reports');
    Route::get('/evaluations',       fn() => Inertia::render('Questionnaire'))->name('evaluations');
    Route::get('/evaluations/start', fn() => Inertia::render('Questionnaire'))->name('evaluations.start');
    Route::get('/questionnaire',     fn() => Inertia::render('Questionnaire'))->name('questionnaire');
});

// ══════════════════════════════════════════════════════════════
// SUPER ADMIN
// ══════════════════════════════════════════════════════════════

Route::get('/admin/login',     fn() => Inertia::render('Admin/AdminLogin'))->name('admin.login');
Route::get('/admin/dashboard', fn() => Inertia::render('Admin/AdminShell'))->name('admin.dashboard');

// ══════════════════════════════════════════════════════════════
// BILLING
// ══════════════════════════════════════════════════════════════

Route::get('/billing',         [BillingController::class, 'index'])->name('billing');
Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
Route::get('/billing/portal',  [BillingController::class, 'portal'])->name('billing.portal');