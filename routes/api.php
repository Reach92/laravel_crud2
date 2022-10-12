<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserStudentsController;
use App\Http\Controllers\Api\SubjectStudentsController;
use App\Http\Controllers\Api\SubjectTeachersController;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('users', UserController::class);

        // User Students
        Route::get('/users/{user}/students', [
            UserStudentsController::class,
            'index',
        ])->name('users.students.index');
        Route::post('/users/{user}/students', [
            UserStudentsController::class,
            'store',
        ])->name('users.students.store');

        Route::apiResource('students', StudentController::class);

        Route::apiResource('teachers', TeacherController::class);

        Route::apiResource('subjects', SubjectController::class);

        // Subject Students
        Route::get('/subjects/{subject}/students', [
            SubjectStudentsController::class,
            'index',
        ])->name('subjects.students.index');
        Route::post('/subjects/{subject}/students', [
            SubjectStudentsController::class,
            'store',
        ])->name('subjects.students.store');

        // Subject Teachers
        Route::get('/subjects/{subject}/teachers', [
            SubjectTeachersController::class,
            'index',
        ])->name('subjects.teachers.index');
        Route::post('/subjects/{subject}/teachers', [
            SubjectTeachersController::class,
            'store',
        ])->name('subjects.teachers.store');
    });
