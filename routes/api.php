<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Admin\CompanyAdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AdminAuthController;

/*
|--------------------------------------------------------------------------
| AUTH PÚBLICA
|--------------------------------------------------------------------------
*/
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| WEBHOOK — Sin middleware (Stripe llama directo)
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS — usuarios normales
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Empresas
    Route::apiResource('companies', CompanyController::class);

    // Empleados y áreas
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('areas',     AreaController::class);

    // Evaluaciones NOM-035
    Route::get('/evaluation/questions/{questionnaire}', [EvaluationController::class, 'questions']);
    Route::post('/evaluation/start',                    [EvaluationController::class, 'start']);
    Route::post('/evaluation/answer',                   [EvaluationController::class, 'answer']);
    Route::post('/evaluation/finish',                   [EvaluationController::class, 'finish']);

    // Resultados y reportes
    Route::get('/results',           [ReportController::class, 'index']);
    Route::post('/reports/generate', [ReportController::class, 'generate']);

    // Billing
    Route::middleware('company.active')->group(function () {
        Route::post('/billing/checkout',    [BillingController::class, 'checkout']);
        Route::post('/billing/change-plan', [BillingController::class, 'changePlan']);
        Route::post('/billing/cancel',      [BillingController::class, 'cancel']);
    });
});

/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/

// Pública — login admin
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Protegidas — auth:admin-api verifica el token, superadmin verifica el rol
Route::middleware(['auth:admin-api', 'superadmin'])->prefix('admin')->group(function () {
    Route::post('/logout',           [AdminAuthController::class,    'logout']);
    Route::get('/me',                [AdminAuthController::class,    'me']);
    Route::get('/companies',         [CompanyAdminController::class, 'index']);
    Route::post('/companies',        [CompanyAdminController::class, 'store']);
    Route::get('/companies/{id}',    [CompanyAdminController::class, 'show']);
    Route::put('/companies/{id}',    [CompanyAdminController::class, 'update']);
    Route::delete('/companies/{id}', [CompanyAdminController::class, 'destroy']);
});