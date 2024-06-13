<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    return response()->json([
        'msg' => 'Unauthorized'
    ], 401);
})->name('login');

// Role Teacher
Route::prefix('admin')->controller(AdminController::class)->group(function (){
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function (){
        
        Route::post('/change_password', 'change_password');
    });
});

// Classroom
Route::prefix('admin/classroom')->controller(ClassroomController::class)->group(function (){
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/', 'index');
        Route::post('/', 'create');
        Route::get('/{id}', 'read');
        Route::post('/{id}/update', 'update');
        Route::delete('/{id}/delete', 'delete');
    });
});

// Student (role teacher)
Route::prefix('admin/student')->controller(StudentController::class)->group(function (){
    Route::get('/', 'getActiveUsers');
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/pending', 'getUnactiveUsers');
        Route::get('/{id}/activation', 'userActivation');
        Route::delete('/{id}/delete', 'delete');
    });
});

// Role student
Route::prefix('user')->controller(UserController::class)->group(function (){
    Route::get('/register', 'register_attributes');
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/', 'dashboard');
        Route::get('/score', 'score');
        Route::post('/change_password', 'change_password');
        Route::get('/logout', 'logout');
    });
    
});

 // Exam
Route::prefix('exam')->controller(ExamController::class)->group(function (){
    Route::middleware('auth:sanctum')->group(function (){
        // Get all exam if not expired
        Route::get('/', 'index');

        // Get exam and question by specific
        Route::get('/{id}', 'read');
        Route::post('/create', 'create');
        Route::post('/attempt', 'attempt');
    });
});

Route::prefix('exam')->controller(AnswerController::class)->group(function (){
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/{exam_id}/answer', 'index');
        Route::get('/{exam_id}/answer/{user_id}', 'read');
        Route::post('/{exam_id}/answer/{user_id}/update', 'update');
    });
});
