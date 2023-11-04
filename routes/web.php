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
    Route::get('products-category', [ProductsController::class, 'category'])->name('product.category');
    Route::get('products-by-category/{id}', [ProductsController::class, 'product'])->name('product.category.product');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('category', CategoryController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('subcategory', SubCategoryController::class);
    Route::get('sub-category', [SubCategoryController::class, 'category'])->name('subcategory.category');
    Route::get('sub-category/subcategory/{id}', [SubCategoryController::class, 'subcategory'])->name('subcategory.subcategory');
    Route::resource('subsubcategory', SubSubCategoryController::class);
    Route::get('sub-sub-category', [SubSubCategoryController::class, 'category'])->name('subsubcategory.category');
    Route::get('sub-sub-category/subcategory/{id}', [SubSubCategoryController::class, 'subcategory'])->name('subsubcategory.subcategory');
    Route::get('sub-sub-category/subsubcategory/{id}', [SubSubCategoryController::class, 'subsubcategory'])->name('subsubcategory.subsubcategory');
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
            Route::post('/store', [CompanyProductsController::class, 'store'])->name('company_product.store');
            Route::post('/update/{id}', [CompanyProductsController::class, 'update'])->name('company_product.update');
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
            Route::post('/store', [CompanyCouponController::class, 'store'])->name('company_coupon.store');
            Route::get('/relation', [CompanyCouponController::class, 'relation'])->name('relation');


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


