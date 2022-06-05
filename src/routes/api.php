<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'protected'], function () {
    //routes to crud user
    Route::get('/users/{elementsPerPage}/{actualPage}/{searchField?}', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::post('user', [UserController::class, 'store']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);
    //route to crud role
    Route::get('/roles/{elementsPerPage}/{actualPage}/{searchField?}', [RoleController::class, 'index']);
    Route::get('role/{id}', [RoleController::class, 'show']);
    Route::put('role/{id}', [RoleController::class, 'update']);
    Route::post('role', [RoleController::class, 'store']);
    Route::delete('role/{id}', [RoleController::class, 'destroy']);
});
