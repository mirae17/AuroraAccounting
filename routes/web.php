<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PurchaseMController;
use App\Http\Controllers\ExpensesMController;
use App\Http\Controllers\DebtorController;

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
    return redirect()->route('login');
});

Auth::routes(['register' => false]);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes for company management
Route::resource('companies', CompanyController::class);

Route::middleware(['auth', 'checkRole:system admin'])->group(function () {
    Route::resource('users', UserController::class);
});

//dashboard
Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard.index');

//sales master
Route::get('/sales/pdf', [SalesController::class, 'exportPDF'])->name('sales.pdf');
Route::resource('sales', SalesController::class);
Route::delete('/sales/{sales}', 'App\Http\Controllers\SalesController@destroy')->name('sales.destroy');


//purchase master
Route::get('/purchaseM/pdf', [PurchaseMController::class, 'exportPDF'])->name('purchaseM.pdf');
Route::resource('purchaseM', PurchaseMController::class);
Route::delete('/purchaseM/{purchaseM}', 'App\Http\Controllers\PurchaseMController@destroy')->name('purchaseM.destroy');

//expenses master
Route::get('/expensesM/pdf', [ExpensesMController::class, 'exportPDF'])->name('expensesM.pdf');
Route::resource('expensesM', ExpensesMController::class);
Route::delete('/expensesM/{expensesM}', 'App\Http\Controllers\ExpensesMController@destroy')->name('expensesM.destroy');

//debtor
Route::resource('debtor', DebtorController::class);
Route::delete('/debtor/{debtors}', 'App\Http\Controllers\DebtorController@destroy')->name('debtor.destroy');


//expenses
Route::resource('expenses', ExpensesController::class);
Route::delete('/expenses/{expenses}', 'App\Http\Controllers\ExpensesController@destroy')->name('expenses.destroy');

//payment methods
Route::resource('payments', PaymentMethodController::class);
Route::delete('/payments/{payments}', 'App\Http\Controllers\PaymentMethodController@destroy')->name('payments.destroy');


//suppliers
Route::resource('suppliers', SupplierController::class);
Route::delete('/suppliers/{suppliers}', 'App\Http\Controllers\SupplierController@destroy')->name('suppliers.destroy');



