<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SocietyController;
use App\Http\Controllers\API\RegisterController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the framework and are assigned the "api"
| middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('societies', SocietyController::class);
    Route::get('/societies/enable/{society}', [SocietyController::class, 'enable'])->name('societies.enable');
    Route::get('/societies/disable/{society}', [SocietyController::class, 'disable'])->name('societies.disable');
    Route::post('/logout', [RegisterController::class, 'logout']);
});
