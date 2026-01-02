<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillingController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

// Route::middleware(['auth:sanctum', 'tenant'])->post('/subscribe', function(Request $request) {
//     dd([
//         'user' => $request->user(),
//         'tenant' => app('tenant'),
//     ]);
// });

Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

    // Dashboard
    // Route::get('/dashboard', DashboardController::class);

    // User
    // Route::apiResource('/users', UserController::class);

    // Billing
    Route::prefix('billing')->group(function () {
        Route::post('/subscribe', [BillingController::class, 'subscribe']);
        Route::post('/change-plan', [BillingController::class, 'changePlan']);
        Route::post('/cancel', [BillingController::class, 'cancel']);
        Route::get('/invoices', [BillingController::class, 'invoices']);
    });

    Route::middleware('subscribed')->group(function () {
        Route::apiResource('/projects', ProjectController::class);
    });
});