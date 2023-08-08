<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpresenceController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/presences', [EpresenceController::class, 'index'])->name('presences.index');
    Route::post('/presences', [EpresenceController::class, 'store'])->name('presences.store');
    Route::patch('/presences', [EpresenceController::class, 'approval'])->name('presences.approval');
});

Route::post('/pairs', [TestController::class, 'pairs'])->name('pairs');
Route::post('/word', [TestController::class, 'word'])->name('word');
