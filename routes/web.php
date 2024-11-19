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
        Route::get('/users', [AdminController::class, 'showUsers'])->name('users');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');

    }
);

// ===== Ticketing Route =====
Route::group(
    ['middleware' => 'ticketing', 'prefix' => 'ticketing', 'as' => 'ticketing.'],
    function () {
        Route::get('/', [TicketingController::class, 'index'])->name('index');
    }
);
