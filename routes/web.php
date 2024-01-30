<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\Company\CompanyHomeController;
use \App\Http\Controllers\Company\WarehouseController;
use \App\Http\Controllers\Company\CompanyUsersController;
use \App\Http\Controllers\Company\CompanyOrderController;
use \App\Http\Controllers\Company\CompanyCouponController;
use \App\Http\Controllers\BannerController;
use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\TableTranslationController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\SubCategoryController;
use \App\Http\Controllers\SubSubCategoryController;
use \App\Http\Controllers\ColorController;
use \App\Http\Controllers\SizesController;
use \App\Http\Controllers\ProductsController;
use \App\Http\Controllers\RoleController;
use \App\Http\Controllers\CompanyController;
use \App\Http\Controllers\LanguageController;
use \App\Http\Controllers\CouponController;
use \App\Http\Controllers\DiscountController;
use \App\Http\Controllers\ImagesController;
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

        Route::get('/api/subcategory/{id}', [SubCategoryController::class, 'getSubcategory'])->name('get_subcategory');
     Route::group(['middleware'=>'authed'], function(){
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
        Route::get('/set-cities', [HomeController::class, 'setCities']);
        Route::resource('banner', BannerController::class);
        Route::resource('images', ImagesController::class);
         Route::delete('/images/delete/{id}', [ImagesController::class, 'destroy'])->name('images.destroy');
        Route::resource('color', ColorController::class);
        Route::resource('size', SizesController::class);
        Route::resource('user', UsersController::class);
        Route::get('user-category', [UsersController::class, 'category'])->name('user.category');
        Route::get('user-by-category/{id}', [UsersController::class, 'user'])->name('user.category.user');
        Route::resource('product', ProductsController::class);
        Route::get('products-category', [ProductsController::class, 'category'])->name('product.category');
        Route::get('allproduct-destroy', [ProductsController::class, 'allproduct_destroy'])->name('product.allproduct_destroy');
        Route::get('products-by-category/{id}', [ProductsController::class, 'product'])->name('product.category.product');
        Route::get('get-warehouses-by-product', [ProductsController::class, 'getWarehousesByProduct']);
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
        Route::resource('coupons', CouponController::class);
        Route::resource('discount', DiscountController::class);
        Route::get('slide-show', [ProductsController::class, 'SlideShow'])->name('slide_show.index');
        Route::get('slide-show/show/{id}', [ProductsController::class, 'SlideShowShow'])->name('slide_show.show');
        Route::get('/slide-show/status/', [ProductsController::class, 'SlideShowStatus'])->name('slide_show.status');
        Route::group(['prefix' => 'order'], function () {
             Route::get('/index/{id}', [OrderController::class, 'index'])->name('order.index');
             Route::get('/category', [OrderController::class, 'category'])->name('order.category');
             Route::get('/show/{id}', [OrderController::class, 'show'])->name('order.show');
             Route::get('/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
        });
         Route::group(['prefix' => 'table'], function () {
             Route::get('translation', [TableTranslationController::class, 'index'])->name('table.index');
             Route::get('show/{type}', [TableTranslationController::class, 'show'])->name('table.show');
             Route::get('table-show', [TableTranslationController::class, 'tableShow'])->name('table.tableShow');
             Route::post('/translation/save/', [TableTranslationController::class, 'translation_save'])->name('table_translation.save');

         });

        Route::group(['prefix' => 'language'], function () {
            Route::get('/', [LanguageController::class, 'index'])->name('language.index');
            Route::get('/language/show/{id}', [LanguageController::class, 'show'])->name('language.show');
            Route::post('/translation/save/', [LanguageController::class, 'translation_save'])->name('translation.save');
            Route::post('/language/change/', [LanguageController::class, 'changeLanguage'])->name('language.change');
            Route::post('/env_key_update', [LanguageController::class, 'env_key_update'])->name('env_key_update.update');
            Route::get('/language/create/', [LanguageController::class, 'create'])->name('languages.create');
            Route::post('/language/added/', [LanguageController::class, 'store'])->name('languages.store');
            Route::get('/language/edit/{id}', [LanguageController::class, 'languageEdit'])->name('language.edit');
            Route::put('/language/update/{id}', [LanguageController::class, 'update'])->name('language.update');
            Route::delete('/language/delete/{id}', [LanguageController::class, 'languageDestroy'])->name('language.destroy');
            Route::post('/language/update/value', [LanguageController::class, 'updateValue'])->name('languages.update_value');
        });

     });
    Route::group(['middleware'=>'company_auth'], function (){
        Route::group(['prefix' => 'companies'], function () {
            Route::get('/', [CompanyHomeController::class, 'index'])->name('company_dashboard');
            Route::group(['prefix' => 'product'], function () {
                Route::resource('warehouse', WarehouseController::class);
                Route::get('warehouse-category', [WarehouseController::class, 'category'])->name('warehouse.category');
                Route::get('product-by-category/{id}', [WarehouseController::class, 'product'])->name('warehouse.category.product');
                Route::get('warehouse-by-category/{id}', [WarehouseController::class, 'warehouse'])->name('warehouse.category.warehouse');
                Route::get('create-warehouse-by-category/{id}', [WarehouseController::class, 'createWarehouse'])->name('warehouse.category.create_warehouse');
                Route::get('get-warehouses-by-product', [WarehouseController::class, 'getWarehousesByProduct'])->name('warehouse.product.warehouse');
            });
            Route::group(['prefix' => 'user'], function () {
                Route::resource('company_user', CompanyUsersController::class)->middleware('is_admin');
            });
            Route::resource('company_coupons', CompanyCouponController::class);
            Route::group(['prefix' => 'company-order'], function () {
                Route::get('/index/{id}', [CompanyOrderController::class, 'index'])->name('company_order.index');
                Route::get('/category', [CompanyOrderController::class, 'category'])->name('company_order.category');
                Route::get('/show/{id}', [CompanyOrderController::class, 'show'])->name('company_order.show');
                Route::get('/destroy/{id}', [CompanyOrderController::class, 'destroy'])->name('company_order.destroy');
            });
        });
    });








