<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD de academias
    Route::apiResource('academies', AcademyController::class);

    // CRUD de cursos
    Route::apiResource('courses', CourseController::class);

    // CRUD de estudiantes
    Route::apiResource('students', StudentController::class);

    // Consultar los cursos de una academia
    Route::get('/academies/{id}/courses', [AcademyController::class, 'courses']);
});