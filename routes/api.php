<?php

use App\Http\Controllers\CallbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\EnsureSignatureValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/callback",[CallbackController::class,"receiveCallback"])->middleware(EnsureSignatureValid::class);

Route::prefix('payment')->group(function () {
    Route::post('/', [PaymentController::class,"create"]);
    Route::get('/{id}', [PaymentController::class,"check"]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
