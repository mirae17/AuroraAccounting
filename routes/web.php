<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SupplierController;

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

//dashboard
Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard.index');

//sales master
Route::resource('sales', SalesController::class);
Route::delete('/sales/{sales}', 'App\Http\Controllers\SalesController@destroy')->name('sales.destroy');


//expenses
Route::resource('expenses', ExpensesController::class);
Route::delete('/expenses/{expenses}', 'App\Http\Controllers\ExpensesController@destroy')->name('expenses.destroy');

//payment methods
Route::resource('payments', PaymentMethodController::class);
Route::delete('/payments/{payments}', 'App\Http\Controllers\PaymentMethodController@destroy')->name('payments.destroy');


//suppliers
Route::resource('suppliers', SupplierController::class);
Route::delete('/suppliers/{suppliers}', 'App\Http\Controllers\ExpensesController@destroy')->name('suppliers.destroy');


