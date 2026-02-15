<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Buyer & Supplier Modules
    Route::apiResource('buyers', \App\Http\Controllers\Api\BuyerController::class)->except(['create', 'edit'])->parameters(['buyers' => 'uuid']);
    Route::apiResource('suppliers', \App\Http\Controllers\Api\SupplierController::class)->except(['create', 'edit'])->parameters(['suppliers' => 'uuid']);

    // Transaction Module
    Route::apiResource('transactions', \App\Http\Controllers\Api\TransactionController::class)->except(['create', 'edit'])->parameters(['transactions' => 'uuid']);

    // LC Module
    Route::apiResource('lcs', \App\Http\Controllers\Api\LCRecordController::class)->except(['create', 'edit'])->parameters(['lcs' => 'uuid']);

    // Shipment Module
    Route::apiResource('shipments', \App\Http\Controllers\Api\ShipmentController::class)->except(['create', 'edit'])->parameters(['shipments' => 'uuid']);

    // Document Vault
    Route::apiResource('documents', \App\Http\Controllers\Api\DocumentController::class)->except(['create', 'edit'])->parameters(['documents' => 'uuid']);

    // Analytics
    Route::get('/analytics/dashboard', [\App\Http\Controllers\Api\AnalyticsController::class, 'dashboard']);
Route::get('/analytics/corridors', [\App\Http\Controllers\Api\AnalyticsController::class, 'corridors']);

    // Compliance & Risk
    Route::get('/compliance/stats', [\App\Http\Controllers\Api\ComplianceController::class, 'getStats']);
    Route::get('/compliance/flagged', [\App\Http\Controllers\Api\ComplianceController::class, 'getFlaggedTransactions']);
    Route::get('/compliance/audit-trail', [\App\Http\Controllers\Api\ComplianceController::class, 'getAuditTrail']);
    Route::post('/compliance/transactions/{uuid}/freeze', [\App\Http\Controllers\Api\ComplianceController::class, 'freezeTransaction']);
});
