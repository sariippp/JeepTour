<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TicketingController;
use Illuminate\Support\Facades\Route;

// ===== Login ======
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== Admin Route =====
Route::group(
    ['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.'],
    function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        // User Management
        Route::get('/users', [AdminController::class, 'showUsers'])->name('users');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        // Financial Management
        Route::get('/financial', [AdminController::class, 'financialDashboard'])->name('financial');
        Route::get('/financial/invoices', [AdminController::class, 'invoiceIndex'])->name('financial.invoices');
        Route::post('/financial/report', [AdminController::class, 'generateFinancialReport'])->name('financial.report');
        Route::get('/financial/invoices/export', [AdminController::class, 'exportToExcel'])->name('invoices.export');
        // Jeep Management
        Route::get('/jeeps', [AdminController::class, 'jeepManagement'])->name('jeeps');
        Route::post('/jeeps/owners', [AdminController::class, 'storeOwner'])->name('jeeps.owners.store');
        Route::put('/jeeps/owners/{id}', [AdminController::class, 'updateOwner'])->name('jeeps.owners.update');
        Route::delete('/jeeps/owners/{id}', [AdminController::class, 'deleteOwner'])->name('jeeps.owners.delete');
        Route::post('/jeeps/vehicles', [AdminController::class, 'storeJeep'])->name('jeeps.vehicles.store');
        Route::put('/jeeps/vehicles/{id}', [AdminController::class, 'updateJeep'])->name('jeeps.vehicles.update');
        Route::delete('/jeeps/vehicles/{id}', [AdminController::class, 'deleteJeep'])->name('jeeps.vehicles.delete');
        }
);

// ===== Ticketing Route =====
Route::group(
    ['middleware' => 'ticketing', 'prefix' => 'ticketing', 'as' => 'ticketing.'],
    function () {
        Route::get('/', [TicketingController::class, 'index'])->name('index');

        Route::get('/invoices', [TicketingController::class, 'invoiceIndex'])->name('invoices');
        
    }
);
