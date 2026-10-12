<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierLedgerController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Logs;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CustomerLedgerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ReportController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL to login
Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Authenticated Application Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:users.view')->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])
            ->name('admin.users.index');
    });

    Route::middleware('permission:users.create')->group(function () {
        Route::get('/admin/users/create', [UserController::class, 'create'])
            ->name('admin.users.create');

        Route::post('/admin/users', [UserController::class, 'store'])
            ->name('admin.users.store');
    });

    Route::middleware('permission:users.edit')->group(function () {
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
            ->name('admin.users.edit');

        Route::put('/admin/users/{user}', [UserController::class, 'update'])
            ->name('admin.users.update');
    });




    /*
    |--------------------------------------------------------------------------
    | Roles and permissions
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:roles.view')->group(function () {
        Route::get('/admin/roles', [RolePermissionController::class, 'index'])
            ->name('admin.roles.index');
    });

    Route::middleware('permission:roles.create')->group(function () {
        Route::get('/admin/roles/create', [RolePermissionController::class, 'create'])
            ->name('admin.roles.create');

        Route::post('/admin/roles', [RolePermissionController::class, 'store'])
            ->name('admin.roles.store');
    });

    Route::middleware('permission:roles.edit')->group(function () {
        Route::get('/admin/roles/{role}/edit', [RolePermissionController::class, 'edit'])
            ->name('admin.roles.edit');

        Route::put('/admin/roles/{role}', [RolePermissionController::class, 'update'])
            ->name('admin.roles.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })
        ->middleware([
            'permission:products.view',
        ])
        ->name('dashboard');




    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index'])
        ->middleware('permission:products.view')
        ->name('categories.index');

    Route::get('/categories/create', [CategoryController::class, 'create'])
        ->middleware('permission:products.create')
        ->name('categories.create');

    Route::post('/categories', [CategoryController::class, 'store'])
        ->middleware('permission:products.create')
        ->name('categories.store');

    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
        ->middleware('permission:products.edit')
        ->name('categories.edit');

    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->middleware('permission:products.edit')
        ->name('categories.update');

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('permission:products.delete')
        ->name('categories.destroy');


    /*
    |--------------------------------------------------------------------------
    | Units
    |--------------------------------------------------------------------------
    */

    Route::get('/units', [UnitController::class, 'index'])
        ->middleware('permission:products.view')
        ->name('units.index');

    Route::get('/units/create', [UnitController::class, 'create'])
        ->middleware('permission:products.create')
        ->name('units.create');

    Route::post('/units', [UnitController::class, 'store'])
        ->middleware('permission:products.create')
        ->name('units.store');

    Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])
        ->middleware('permission:products.edit')
        ->name('units.edit');

    Route::put('/units/{unit}', [UnitController::class, 'update'])
        ->middleware('permission:products.edit')
        ->name('units.update');

    Route::delete('/units/{unit}', [UnitController::class, 'destroy'])
        ->middleware('permission:products.delete')
        ->name('units.destroy');


    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('permission:products.view')
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->middleware('permission:products.create')
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('permission:products.create')
        ->name('products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->middleware('permission:products.edit')
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->middleware('permission:products.edit')
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('permission:products.delete')
        ->name('products.destroy');


    /*
    |--------------------------------------------------------------------------
    | Stock Management
    |--------------------------------------------------------------------------
    */

    // Stock overview
    Route::get('/stock', [StockController::class, 'index'])
        ->middleware('permission:stock.view')
        ->name('stock.index');

    // Opening stock
    Route::get('/stock/opening', [StockController::class, 'opening'])
        ->middleware('permission:stock.adjust')
        ->name('stock.opening');

    Route::post('/stock/opening', [StockController::class, 'storeOpening'])
        ->middleware('permission:stock.adjust')
        ->name('stock.opening.store');

    // Stock summary
    Route::get('/stock/summary', [StockController::class, 'summary'])
        ->middleware('permission:stock.view')
        ->name('stock.summary');

    // Stock adjustment
    Route::get('/stock/adjustment', [StockController::class, 'adjustment'])
        ->middleware('permission:stock.adjust')
        ->name('stock.adjustment');

    Route::post('/stock/adjustment', [StockController::class, 'storeAdjustment'])
        ->middleware('permission:stock.adjust')
        ->name('stock.adjustment.store');


    /*
    |--------------------------------------------------------------------------
    | Suppliers
    |--------------------------------------------------------------------------
    */

    Route::resource('suppliers', SupplierController::class)
        ->middleware([
            'index'   => 'permission:suppliers.view',
            'create'  => 'permission:suppliers.create',
            'store'   => 'permission:suppliers.create',
            'edit'    => 'permission:suppliers.edit',
            'update'  => 'permission:suppliers.edit',
            'destroy' => 'permission:suppliers.delete',
        ]);


    /*
    |--------------------------------------------------------------------------
    | Supplier Ledger
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/suppliers/{supplier}/ledger',
        [SupplierLedgerController::class, 'show']
    )
        ->middleware('permission:suppliers.view')
        ->name('suppliers.ledger');


    /*
    |--------------------------------------------------------------------------
    | Purchases    |--------------------------------------------------------------------------
    */

    // Purchase list
    Route::get('/purchases', [PurchaseController::class, 'index'])
        ->middleware('permission:purchases.view')
        ->name('purchases.index');

    // Create purchase form
    Route::get('/purchases/create', [PurchaseController::class, 'create'])
        ->middleware('permission:purchases.create')
        ->name('purchases.create');

    // Store purchase
    Route::post('/purchases', [PurchaseController::class, 'store'])
        ->middleware('permission:purchases.create')
        ->name('purchases.store');

    // Purchase details
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])
        ->middleware('permission:purchases.view')
        ->name('purchases.show');


    /*
    |--------------------------------------------------------------------------
    | Supplier Payments
    |--------------------------------------------------------------------------
    */

    // Supplier payment history
    Route::get(
        '/suppliers/{supplier}/payments',
        [SupplierPaymentController::class, 'index']
    )
        ->middleware('permission:suppliers.view')
        ->name('suppliers.payments.index');

    // Create supplier payment
    Route::get(
        '/suppliers/{supplier}/payments/create',
        [SupplierPaymentController::class, 'create']
    )
        ->middleware('permission:suppliers.edit')
        ->name('suppliers.payments.create');

    // Store supplier payment
    Route::post(
        '/suppliers/{supplier}/payments',
        [SupplierPaymentController::class, 'store']
    )
        ->middleware('permission:suppliers.edit')
        ->name('suppliers.payments.store');

    /*
|--------------------------------------------------------------------------
| Customers
|--------------------------------------------------------------------------
*/

    Route::resource('customers', CustomerController::class)
        ->middleware([
            'index'   => 'permission:customers.view',
            'create'  => 'permission:customers.create',
            'store'   => 'permission:customers.create',
            'edit'    => 'permission:customers.edit',
            'update'  => 'permission:customers.edit',
            'destroy' => 'permission:customers.delete',
        ]);

    /*
|--------------------------------------------------------------------------
| Sales
|--------------------------------------------------------------------------
*/

    Route::get('/sales', [SaleController::class, 'index'])
        ->middleware('permission:sales.view')
        ->name('sales.index');

    Route::get('/sales/create', [SaleController::class, 'create'])
        ->middleware('permission:sales.create')
        ->name('sales.create');

    Route::get('/sales/{sale}', [SaleController::class, 'show'])
        ->middleware('permission:sales.view')
        ->name('sales.show');

    Route::post('/sales', [SaleController::class, 'store'])
        ->middleware('permission:sales.create')
        ->name('sales.store');




    Route::prefix('customers/{customer}/payments')
        ->name('customers.payments.')
        ->group(function () {
            Route::get('/', [CustomerPaymentController::class, 'index'])
                ->middleware('permission:customers.view')
                ->name('index');

            Route::get('/create', [CustomerPaymentController::class, 'create'])
                ->middleware('permission:customers.edit')
                ->name('create');

            Route::post('/', [CustomerPaymentController::class, 'store'])
                ->middleware('permission:customers.edit')
                ->name('store');

            Route::get('/{payment}', [CustomerPaymentController::class, 'show'])
                ->middleware('permission:customers.view')
                ->name('show');
        });



    Route::get(
        '/customers/{customer}/ledger',
        [CustomerLedgerController::class, 'show']
    )
        ->middleware('permission:customers.view')
        ->name('customers.ledger');



    /*
|--------------------------------------------------------------------------
| Expense Routes
|--------------------------------------------------------------------------
*/


    Route::prefix('expenses')
        ->name('expenses.')
        ->group(function () {
            Route::get('/', [ExpenseController::class, 'index'])
                ->middleware('permission:expenses.view')
                ->name('index');

            Route::get('/create', [ExpenseController::class, 'create'])
                ->middleware('permission:expenses.create')
                ->name('create');

            Route::post('/', [ExpenseController::class, 'store'])
                ->middleware('permission:expenses.create')
                ->name('store');
        });



    /*
|--------------------------------------------------------------------------
| Accounts Routes
|--------------------------------------------------------------------------
*/


    Route::prefix('accounts')
        ->name('accounts.')
        ->group(function () {
            Route::get('/', [AccountController::class, 'index'])
                ->middleware('permission:accounts.view')
                ->name('index');

            Route::get('/create', [AccountController::class, 'create'])
                ->middleware('permission:accounts.create')
                ->name('create');

            Route::post('/', [AccountController::class, 'store'])
                ->middleware('permission:accounts.create')
                ->name('store');

            Route::get('/{account}/transactions', [AccountController::class, 'transactions'])
                ->middleware('permission:accounts.view')
                ->name('transactions');

            Route::get('/{account}/transactions/create', [AccountController::class, 'createTransaction'])
                ->middleware('permission:accounts.create')
                ->name('transactions.create');

            Route::post('/{account}/transactions', [AccountController::class, 'storeTransaction'])
                ->middleware('permission:accounts.create')
                ->name('transactions.store');
        });






    /*
|--------------------------------------------------------------------------
| Reports Routes
|--------------------------------------------------------------------------
*/

    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permission:reports.view')
        ->name('reports.index');




    /*
|--------------------------------------------------------------------------
| Logs
|--------------------------------------------------------------------------
*/

    Route::resource('logs', Logs::class)
        ->middleware([
            'index'   => 'permission:customers.view',
        ]);
});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Laravel Breeze authentication routes.
|
*/

require __DIR__ . '/auth.php';
