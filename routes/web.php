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
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryMasterController;
use App\Http\Controllers\CustomerDetailController;
use App\Http\Controllers\CompanyMaintenanceController;


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

// Redirect root URL to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Disable registration and set up authentication routes
Auth::routes(['register' => false]);

// Redirect to dashboard after login
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');


// Company management routes
Route::resource('company', CompanyController::class);

// Restrict user management to system admin
Route::middleware('checkRole:system admin')->group(function () {
    Route::resource('users', UserController::class);
});



// Sales master routes
Route::get('/sales/pdf', [SalesController::class, 'exportPDF'])->name('sales.pdf');
Route::resource('sales', SalesController::class);
Route::delete('/sales/{sales}', [SalesController::class, 'destroy'])->name('sales.destroy');

// Purchase master routes
Route::get('/purchaseM/pdf', [PurchaseMController::class, 'exportPDF'])->name('purchaseM.pdf');
Route::resource('purchaseM', PurchaseMController::class);
Route::delete('/purchaseM/{purchaseM}', [PurchaseMController::class, 'destroy'])->name('purchaseM.destroy');

// Expenses master routes
Route::get('/expensesM/pdf', [ExpensesMController::class, 'exportPDF'])->name('expensesM.pdf');
Route::resource('expensesM', ExpensesMController::class);
Route::delete('/expensesM/{expensesM}', [ExpensesMController::class, 'destroy'])->name('expensesM.destroy');

Route::get('/inventoryM/pdf', [InventoryMasterController::class, 'exportPDF'])->name('inventoryM.pdf');
Route::resource('inventoryM', InventoryMasterController::class);
Route::delete('/inventoryM/{inventoryM}', [InventoryMasterController::class, 'destroy'])->name('inventoryM.destroy');


Route::get('/customerDetail/pdf', [CustomerDetailController::class, 'exportPDF'])->name('customerDetail.pdf');
Route::resource('customerDetail', CustomerDetailController::class);
Route::delete('/customerDetail/{customerDetail}', [CustomerDetailController::class, 'destroy'])->name('customerDetail.destroy');

Route::get('/companyMaintenance/pdf', [CompanyMaintenanceController::class, 'exportPDF'])->name('companyMaintenance.pdf');
Route::resource('companyMaintenance', CompanyMaintenanceController::class);

// Debtor routes
Route::resource('debtor', DebtorController::class);
Route::delete('/debtor/{debtors}', [DebtorController::class, 'destroy'])->name('debtor.destroy');

// Employee routes
Route::resource('employees', EmployeeController::class);
Route::delete('/employees/{employees}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

// Expenses routes
Route::resource('expenses', ExpensesController::class);
Route::delete('/expenses/{expenses}', [ExpensesController::class, 'destroy'])->name('expenses.destroy');

// Payment methods routes
Route::resource('payments', PaymentMethodController::class);
Route::delete('/payments/{payments}', [PaymentMethodController::class, 'destroy'])->name('payments.destroy');

// Supplier routes
Route::resource('suppliers', SupplierController::class);
Route::delete('/suppliers/{suppliers}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

//Invetory routes
Route::resource('inventory', InventoryController::class);
Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

//Product
Route::resource('product', ProductController::class);
Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');



