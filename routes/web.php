<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ZoneController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::group(["prefix" => "/auth"], function () {
    Route::get('/get-active-token', [TokenController::class, 'getActiveToken'])->name('token.getActiveToken');
    Route::get('/login', [TokenController::class, 'getActiveToken'])->name('token.getActiveToken');
});*/

Route::group(["prefix" => "/auth"], function () {
    Route::get('/get-active-token', [AuthController::class, 'getActiveToken'])->name('auth.getActiveToken');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::group(['middleware' => 'auth:api' , "prefix" => "/zone"], function () {
    Route::get('/list', [ZoneController::class, 'list'])->middleware('can:zone.list')->name('zone.list');
    Route::post('/create', [ZoneController::class, 'create'])->middleware('can:zone.create')->name('zone.create');
    Route::put('/update/{id}', [ZoneController::class, 'update'])->middleware('can:zone.update')->name('zone.update');
    Route::delete('/delete/{id}', [ZoneController::class, 'delete'])->middleware('can:zone.delete')->name('zone.delete');
    Route::get('/get/{id}', [ZoneController::class, 'get'])->middleware('can:zone.get')->name('zone.get');
});

Route::get('/', function () {
    return view('welcome');
});
