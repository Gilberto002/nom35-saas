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
| WEBHOOK
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS — usuarios normales
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user',    [AuthController::class, 'user']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('areas',     AreaController::class);

    Route::get('/evaluation/questions/{questionnaire}', [EvaluationController::class, 'questions']);
    Route::post('/evaluation/start',                    [EvaluationController::class, 'start']);
    Route::post('/evaluation/answer',                   [EvaluationController::class, 'answer']);
    Route::post('/evaluation/finish',                   [EvaluationController::class, 'finish']);

    Route::get('/results',           [ReportController::class, 'index']);
    Route::post('/reports/generate', [ReportController::class, 'generate']);

    Route::middleware(\App\Http\Middleware\EnsureCompanyIsActive::class)->group(function () {
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
Route::prefix('admin')->group(function () {

    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware(\App\Http\Middleware\SuperAdmin::class)->group(function () {

        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me',      [AdminAuthController::class, 'me']);

        Route::get('/dashboard',                           [CompanyAdminController::class, 'dashboard']);
        Route::get('/companies',                           [CompanyAdminController::class, 'index']);
        Route::post('/companies',                          [CompanyAdminController::class, 'store']);
        Route::get('/companies/{company}',                 [CompanyAdminController::class, 'show']);
        Route::put('/companies/{company}',                 [CompanyAdminController::class, 'update']);
        Route::patch('/companies/{company}/toggle-status', [CompanyAdminController::class, 'toggleStatus']);
        Route::delete('/companies/{company}',              [CompanyAdminController::class, 'destroy']);

        Route::get('/plans',        [CompanyAdminController::class, 'plans']);
        Route::post('/plans',       [CompanyAdminController::class, 'storePlan']);
        Route::put('/plans/{plan}', [CompanyAdminController::class, 'updatePlan']);
    });
});