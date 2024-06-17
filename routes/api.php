<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('register', 'App\Http\Controllers\AuthController@register');
Route::post('logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:api');
Route::post('password/reset', 'App\Http\Controllers\AuthController@resetPassword');

Route::name('api.')->group(function(){
    Route::prefix('/tasks')->group(function(){
        Route::get('/', [App\Http\Controllers\TaskController::class, 'index'])->name('index_task');
        Route::get('/{id}', [App\Http\Controllers\TaskController::class, 'show'])->name('single_task');

        Route::post('/', [App\Http\Controllers\TaskController::class, 'store'])->name('store_task');
        Route::put('/{id}', [App\Http\Controllers\TaskController::class, 'update'])->name('update_task');

        Route::delete('/{id}', [App\Http\Controllers\TaskController::class, 'delete'])->name('delete_task');
    });

    Route::prefix('/user')->group(function(){
        Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show']);
        Route::put('/{id}', [App\Http\Controllers\UserController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'delete']);
    });

    Route::get('users', [App\Http\Controllers\UserController::class, 'index']);

});
