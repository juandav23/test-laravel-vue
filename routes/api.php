<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\API\RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [\App\Http\Controllers\API\RegisterController::class, 'list']);
});

Route::middleware('auth:sanctum')->controller(\App\Http\Controllers\CompanyController::class)
    ->prefix('company')
    ->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
    });

Route::middleware('auth:sanctum')->controller(\App\Http\Controllers\ClientController::class)
    ->prefix('client')
    ->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
    });

Route::middleware('auth:sanctum')->controller(\App\Http\Controllers\PositionsController::class)
    ->prefix('positions')
    ->group(function () {
        Route::get('/{companyid}', 'index');
        Route::get('position/{id}', 'getPosition');
        Route::post('', 'store');
        Route::post('applicant', 'saveApplicant');
        Route::delete('applicant/{id}', 'deleteApplicant');
        Route::get('winner/{id}', 'markWinner');
    });
