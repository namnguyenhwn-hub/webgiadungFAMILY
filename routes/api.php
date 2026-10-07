<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SepayPaymentController;

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

// Webhook endpoint dành cho SePay khi gọi qua tiền tố /api
Route::match(['get', 'post'], '/sepay/webhook', [SepayPaymentController::class, 'webhook'])->name('api.sepay.webhook');
