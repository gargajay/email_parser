<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuccessfulEmailController;
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

// auth routes

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('webhook',[AuthController::class,'webhook']);




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/emails', [SuccessfulEmailController::class, 'store']);
    Route::get('/emails/{id}', [SuccessfulEmailController::class, 'show']);
    Route::put('/emails/{id}', [SuccessfulEmailController::class, 'update']);
    Route::get('/emails', [SuccessfulEmailController::class, 'index']);
    Route::delete('/emails/{id}', [SuccessfulEmailController::class, 'destroy']);
    Route::post('logout',[AuthController::class,'logout']);





});