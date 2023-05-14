<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::middleware('jwt.auth')->post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'prefix' => 'lead'
], function () {
    Route::middleware('jwt.auth')->get('/', [CandidateController::class, 'index']);
    Route::middleware('jwt.auth')->get('/{id}', [CandidateController::class, 'show']);
    Route::middleware('jwt.auth')->post('/', [CandidateController::class, 'store']);
    // Route::post('logout', [AuthController::class, 'logout']);
    // Route::post('refresh', [AuthController::class, 'refresh']);
    // Route::post('me', [AuthController::class, 'me']);
});

