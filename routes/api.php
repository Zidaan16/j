<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Redirect kesini jika user tidak memiliki token
Route::get('/', function (){
    return response()->json([
        'msg' => 'Unauthorized'
    ], 401);
})->name('login');

// Role Teacher
Route::prefix('admin')->controller(AdminController::class)->group(function (){
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function (){
        
    });
});

// Role student
Route::prefix('user')->controller(UserController::class)->group(function (){
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/', 'profile');
        Route::post('/change_password', 'change_password');
        Route::get('/logout', 'logout');
    });
    
});

// Student
Route::prefix('user')->controller(StudentController::class)->group(function (){
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/active', 'getActiveUsers');
        Route::get('/unactive', 'getUnactiveUsers');
        Route::put('/activation/{id}', 'userActivation');
        Route::delete('/delete/{id}', 'delete');
    });
});

 // Exam
Route::prefix('exam')->controller(ExamController::class)->group(function (){
    Route::middleware('auth:sanctum')->group(function (){
        // Get all exam if not expired
        Route::get('/', 'index');

        // Get exam and question by specific
        Route::get('/{id}', 'view');
        Route::post('/create', 'create');
        Route::post('/attempt', 'attempt');
    });
});

// Classroom
Route::prefix('classroom')->controller(ClassroomController::class)->group(function (){
    Route::get('/', 'index');
    Route::middleware('auth:sanctum')->group(function (){
        Route::post('/', 'create');
        Route::get('/{id}', 'read');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });
});
