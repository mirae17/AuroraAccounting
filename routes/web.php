<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//sales master
Route::get('/sales', 'App\Http\Controllers\SalesController@index')->name('sales.index');
Route::get('/sales/create', 'App\Http\Controllers\SalesController@create')->name('sales.create');
Route::post('/sales', 'App\Http\Controllers\SalesController@store')->name('sales.store');
Route::get('/sales/{sales_master}', 'App\Http\Controllers\SalesController@show')->name('sales.show');
Route::get('/sales/{sales_master}/edit', 'App\Http\Controllers\SalesController@edit')->name('sales.edit');
Route::put('/sales/{sales_master}', 'App\Http\Controllers\SalesController@update')->name('sales.update');
Route::delete('/sales/{sales_master}', 'App\Http\Controllers\SalesController@destroy')->name('sales.destroy');

//dashboard
Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard.index');


