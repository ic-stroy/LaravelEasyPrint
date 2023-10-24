<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\SubCategoryController;
use \App\Http\Controllers\ColorController;
use \App\Http\Controllers\SizesController;
use \App\Http\Controllers\ProductsController;
use \App\Http\Controllers\AddressController;
use \App\Http\Controllers\RoleController;
use \App\Http\Controllers\CompanyController;

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

Auth::routes();

// Route::group(['middleware'=>'auth'], function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('color', ColorController::class);
    Route::resource('size', SizesController::class);
    Route::resource('user', UsersController::class);
    Route::resource('product', ProductsController::class);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('category', CategoryController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('subcategory', SubCategoryController::class);
    Route::resource('address', AddressController::class);
    Route::resource('role', RoleController::class);

    // Route::group(['prefix' => 'seller'], function () {
    //     Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    //     // Route::get('/home', [HomeController::class, 'index'])->name('home');
    //     Route::resource('color', ColorController::class);
    //     Route::resource('size', SizesController::class);
    //     Route::resource('user', UsersController::class);
    //     Route::resource('product', ProductsController::class);
    //     Route::resource('category', CategoryController::class);
    //     Route::resource('subcategory', SubCategoryController::class);
    // });


// });


