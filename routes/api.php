<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

//Route::post('password/reset', 'App\Http\Controllers\AuthController@resetPassword');

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::name('api.')->group(function(){
        Route::prefix('/tasks')->group(function(){
            Route::get('/', [TaskController::class, 'index'])->name('index_task');
            Route::get('/{id}', [TaskController::class, 'show'])->name('single_task');

            Route::post('/', [TaskController::class, 'store'])->name('store_task');
            Route::put('/{id}', [TaskController::class, 'update'])->name('update_task');
            Route::patch('tasks/{id}/status', [TaskController::class, 'updateStatus']);

            Route::delete('/{id}', [TaskController::class, 'delete'])->name('delete_task');
        });

        Route::prefix('/user')->group(function(){
            Route::get('/{id}', [UserController::class, 'show']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'delete']);
        });
    });
});


