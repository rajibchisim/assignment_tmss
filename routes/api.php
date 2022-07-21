<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
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

Route::group(
    ['prefix' =>'auth'],
function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});


Route::group(
[
    'middleware' => 'auth:api',
    'prefix' => '/departments'

], function() {
    Route::delete('/{department}/delete', [DepartmentController::class, 'destroy']);
    Route::patch('/{department}/update', [DepartmentController::class, 'update']);
    Route::get('/search', [DepartmentController::class, 'search']);
    Route::post('/store', [DepartmentController::class, 'store']);
    Route::get('/{department}', [DepartmentController::class, 'show']);
    Route::get('/', [DepartmentController::class, 'index']);
});



Route::group(
[
    'middleware' => 'auth:api',
    'prefix' => '/batches'

], function() {
    Route::patch('/{batch}/update', [BatchController::class, 'update']);
    Route::delete('/{batch}/delete', [BatchController::class, 'destroy']);
    Route::post('/store', [BatchController::class, 'store']);
    Route::get('/search', [BatchController::class, 'search']);
    Route::get('/{batch}/check', [BatchController::class, 'check']);
    Route::get('/{batch}', [BatchController::class, 'show']);
    Route::get('/', [BatchController::class, 'index']);
});


Route::group(
[
    'middleware' => 'auth:api',
    'prefix' => '/students'

], function() {
    Route::patch('/{student}/update-transfer', [StudentController::class, 'updateTransfer']);
    Route::patch('/{student}/update', [StudentController::class, 'update']);
    Route::delete('/{student}/delete', [StudentController::class, 'destroy']);
    Route::post('/store', [StudentController::class, 'store']);
    Route::get('/search', [StudentController::class, 'search']);
    Route::get('/{student}', [StudentController::class, 'show']);
    Route::get('/', [StudentController::class, 'index']);
});



Route::group(
[
    'middleware' => 'auth:api',
    'prefix' => '/results'

], function() {
    Route::patch('/{result}/update', [ResultController::class, 'update']);
    Route::delete('/{result}/delete', [ResultController::class, 'destroy']);
    Route::post('/store', [ResultController::class, 'store']);
    Route::get('/search', [ResultController::class, 'search']);
    Route::get('/{result}', [ResultController::class, 'show']);
    Route::get('/', [ResultController::class, 'index']);
});

