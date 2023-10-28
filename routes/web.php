<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\Company\CompanyHomeController;
use \App\Http\Controllers\Company\CompanyProductsController;
use \App\Http\Controllers\Company\CompanyUsersController;
use \App\Http\Controllers\Company\CompanyCouponController;
use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\SubCategoryController;
use \App\Http\Controllers\SubSubCategoryController;
use \App\Http\Controllers\ColorController;
use \App\Http\Controllers\SizesController;
use \App\Http\Controllers\ProductsController;
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

Route::group(['middleware'=>'authed'], function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('color', ColorController::class);
    Route::resource('size', SizesController::class);
    Route::resource('user', UsersController::class);
    Route::resource('product', ProductsController::class);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('category', CategoryController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('subcategory', SubCategoryController::class);
    Route::resource('subsubcategory', SubSubCategoryController::class);
    Route::resource('role', RoleController::class);
});
Route::group(['middleware'=>'company_auth'], function (){
    Route::group(['prefix' => 'companies'], function () {

        Route::get('/', [CompanyHomeController::class, 'index'])->name('company_dashboard');

        Route::group(['prefix' => 'product'], function () {
            Route::get('/', [CompanyProductsController::class, 'index'])->name('company_product.index');
            Route::get('/show/{id}', [CompanyProductsController::class, 'show'])->name('company_product.show');
            Route::get('/edit/{id}', [CompanyProductsController::class, 'edit'])->name('company_product.edit');
            Route::get('/create', [CompanyProductsController::class, 'create'])->name('company_product.create');
        });
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [CompanyUsersController::class, 'index'])->name('company_user.index');
            Route::get('/show/{id}', [CompanyUsersController::class, 'show'])->name('company_user.show');
            Route::get('/edit/{id}', [CompanyUsersController::class, 'edit'])->name('company_user.edit');
            Route::get('/create', [CompanyUsersController::class, 'create'])->name('company_user.create');
        });


        Route::group(['prefix' => 'coupon'], function () {
            Route::get('/', [CompanyCouponController::class, 'index'])->name('company_coupon.index');
            Route::get('/create', [CompanyCouponController::class, 'create'])->name('company_coupon.create');

            // Route::get('/show/{id}', [CompanyCouponController::class, 'show'])->name('company_user.show');
            // Route::get('/edit/{id}', [CompanyCouponController::class, 'edit'])->name('company_user.edit');
            // Route::get('/create', [CompanyCouponController::class, 'create'])->name('company_user.create');
        });

        // Route::resource('product', CompanyProductsController::class);

        // Route::get('/home', [HomeController::class, 'index'])->name('home');
        // Route::resource('color', ColorController::class);
        // Route::resource('size', SizesController::class);
        // Route::resource('user', UsersController::class);
        // Route::resource('product', ProductsController::class);
        // Route::resource('category', CategoryController::class);
        // Route::resource('subcategory', SubCategoryController::class);
    });
});


