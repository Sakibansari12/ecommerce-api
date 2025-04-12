<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::middleware('isLogin')->group(function () {
    Route::get('/', [AuthController::class, 'RegisterStore'])->name('login');
    Route::post('/user-register', [AuthController::class, 'RegisterCreate'])->name('user-register');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('user-login');
    Route::post('admin-login', [AuthController::class, 'authuser'])->name('ck_login');
});
Route::middleware(['auth'])->get('dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
Route::middleware(['auth'])->get('logout-user', [AuthController::class, 'logoutUser'])->name('logout.user');

Route::middleware(['auth'])->group(function () {
    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('category/update', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/{id}', [CategoryController::class, 'delete'])->name('category.delete');

    Route::get('product', [ProductController::class, 'index'])->name('product.index');
    Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('product/update', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/{id}', [ProductController::class, 'delete'])->name('product.delete');

    
});


