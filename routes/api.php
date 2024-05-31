<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect kesini jika user tidak memiliki token
Route::get('/', function (){
    return response()->json([
        'msg' => 'Unauthorized'
    ], 401);
})->name('login');

Route::prefix('user')->controller(UserController::class)->group(function (){
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/', 'profile');
        Route::post('/change_password', 'change_password');
        Route::get('/logout', 'logout');
    });

});

Route::prefix('quiz')->controller(QuizController::class)->middleware('auth:sanctum')->group(function (){
    Route::get('/', 'index');
});
