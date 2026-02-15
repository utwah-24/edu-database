<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\GradeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Department routes
Route::prefix('departments')->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::post('/', [DepartmentController::class, 'store']);
    Route::get('/{id}', [DepartmentController::class, 'show']);
    Route::put('/{id}', [DepartmentController::class, 'update']);
    Route::delete('/{id}', [DepartmentController::class, 'destroy']);
    Route::get('/{id}/teachers', [DepartmentController::class, 'teachers']);
    Route::get('/{id}/courses', [DepartmentController::class, 'courses']);
});

// Teacher routes
Route::prefix('teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'index']);
    Route::post('/', [TeacherController::class, 'store']);
    Route::get('/{id}', [TeacherController::class, 'show']);
    Route::put('/{id}', [TeacherController::class, 'update']);
    Route::delete('/{id}', [TeacherController::class, 'destroy']);
    Route::get('/{id}/courses', [TeacherController::class, 'courses']);
});

// Student routes
Route::prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index']);
    Route::post('/', [StudentController::class, 'store']);
    Route::get('/{id}', [StudentController::class, 'show']);
    Route::put('/{id}', [StudentController::class, 'update']);
    Route::delete('/{id}', [StudentController::class, 'destroy']);
    Route::get('/{id}/enrollments', [StudentController::class, 'enrollments']);
    Route::get('/{id}/courses', [StudentController::class, 'courses']);
});

// Course routes
Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::post('/', [CourseController::class, 'store']);
    Route::get('/{id}', [CourseController::class, 'show']);
    Route::put('/{id}', [CourseController::class, 'update']);
    Route::delete('/{id}', [CourseController::class, 'destroy']);
    Route::get('/{id}/students', [CourseController::class, 'students']);
    Route::get('/{id}/enrollments', [CourseController::class, 'enrollments']);
});

// Enrollment routes
Route::prefix('enrollments')->group(function () {
    Route::get('/', [EnrollmentController::class, 'index']);
    Route::post('/', [EnrollmentController::class, 'store']);
    Route::get('/{id}', [EnrollmentController::class, 'show']);
    Route::put('/{id}', [EnrollmentController::class, 'update']);
    Route::delete('/{id}', [EnrollmentController::class, 'destroy']);
    Route::get('/{id}/grades', [EnrollmentController::class, 'grades']);
});

// Grade routes
Route::prefix('grades')->group(function () {
    Route::get('/', [GradeController::class, 'index']);
    Route::post('/', [GradeController::class, 'store']);
    Route::get('/{id}', [GradeController::class, 'show']);
    Route::put('/{id}', [GradeController::class, 'update']);
    Route::delete('/{id}', [GradeController::class, 'destroy']);
});
