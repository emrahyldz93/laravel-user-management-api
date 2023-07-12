<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/{id}', [AuthController::class, 'user'])->middleware('throttle:100,1');
    Route::get('/users', [AuthController::class, 'users'])->middleware('throttle:100,1');
    Route::put('/users/{id}',  [AuthController::class, 'update'])->middleware('throttle:100,1');
    Route::delete('/users/{id}',  [AuthController::class, 'delete'])->middleware('throttle:100,1');
});

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:100,1');
Route::post('/register', [AuthController::class, 'createUser'])->middleware('throttle:100,1');
