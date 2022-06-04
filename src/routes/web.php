<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


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
