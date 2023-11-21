<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Frontent  Api routes
 */

Route::get('get-districts', [CompanyController::class, 'getCities']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/googleauth', [AuthController::class, 'redirectGoogle'])->name('redirectGoogle');
Route::get('/googleauth/callback', [AuthController::class, 'callbackGoogle'])->name('callbackGoogle');
Route::get('/googleauth/user', [AuthController::class, 'responseUser'])->name('responseUser');
// Route::group(['prefix' => 'header'], function () {
//     Route::get('/list', [ProductController::class, 'index']);
//     // Route::get('/show/{id}', [CompanyProductsController::class, 'show'])->name('company_product.show');
//     // Route::get('/edit/{id}', [CompanyProductsController::class, 'edit'])->name('company_product.edit');
//     // Route::get('/create', [CompanyProductsController::class, 'create'])->name('company_product.create');
//     // Route::post('/store', [CompanyProductsController::class, 'store'])->name('company_product.store');
//     // Route::post('/update/{id}', [CompanyProductsController::class, 'update'])->name('company_product.update');
// });

Route::group(['prefix' => 'product'], function () {
    Route::get('/list', [ProductController::class, 'index']);
    Route::get('/show/warehouse_product', [ProductController::class, 'show']);

    // Route::get('/show/{id}', [CompanyProductsController::class, 'show'])->name('company_product.show');
    // Route::get('/edit/{id}', [CompanyProductsController::class, 'edit'])->name('company_product.edit');
    // Route::get('/create', [CompanyProductsController::class, 'create'])->name('company_product.create');
    // Route::post('/store', [CompanyProductsController::class, 'store'])->name('company_product.store');
    // Route::post('/update/{id}', [CompanyProductsController::class, 'update'])->name('company_product.update');
});

Route::get('get-categories-by-product/{id}', [ProductController::class, 'getCategoriesByProduct'])->name('get_categories_by_product');
Route::get('profile-info', [CategoryController::class, 'profileInfo']);
Route::get('get-banner', [BannerController::class, 'getBanner']);
Route::get('get-products-by-categories', [CategoryController::class, 'getProductsByCategories']);
Route::get('get-products-by-category', [CategoryController::class, 'getProductsByCategory']);
Route::get('get-categories', [CategoryController::class, 'getCategories']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('personal-information', [UsersController::class, 'setPersonalInformation']);
    Route::get('personal-information', [UsersController::class, 'getPersonalInformation']);
    Route::post('set-address', [UsersController::class, 'setAddress']);
    Route::post('set-warehouse', [OrderController::class, 'setWarehouse']);
    Route::get('get-basket', [OrderController::class, 'getBasket']);
    Route::get('product', [ProductController::class, 'getProduct']);
    Route::post('logout', [AuthController::class, 'logout']);
});
