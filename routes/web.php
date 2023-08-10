<?php

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
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::get('getUsersData', [App\Http\Controllers\UserController::class, 'getUsersData']);
Route::get('/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');Route::put('/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::delete('/destroy/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
Route::post('userActiveInactiveStatus/{type}/{id}', [App\Http\Controllers\UserController::class, 'userActiveInactiveStatus']);


Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])->name('clients');
Route::get('clients/create', [App\Http\Controllers\ClientController::class, 'create'])->name('clients.create');
Route::post('clients/store', [App\Http\Controllers\ClientController::class, 'store'])->name('clients.store');
Route::get('getClientsData', [App\Http\Controllers\ClientController::class, 'getClientsData']);
Route::get('clients/edit/{id}', [App\Http\Controllers\ClientController::class, 'edit'])->name('clients.edit');Route::put('clients/update/{id}', [App\Http\Controllers\ClientController::class, 'update'])->name('clients.update');
Route::delete('clients/destroy/{id}', [App\Http\Controllers\ClientController::class, 'destroy']);


Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', App\Http\Controllers\RoleController::class);
});