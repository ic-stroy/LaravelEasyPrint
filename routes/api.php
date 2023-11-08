<?php

// use App\Http\Controllers\ProductsController;
 use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Api\ProductController;

 use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::get('subcategory/{id}', [ProductsController::class, 'getSubcategory'])->name('get_subcategory');
Route::get('subcategory/{id}', [SubCategoryController::class, 'getSubcategory'])->name('get_subcategory');
Route::get('get-districts', [CompanyController::class, 'getCities']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['prefix' => 'product'], function () {
    Route::get('/list', [ProductController::class, 'index']);
    // Route::get('/show/{id}', [CompanyProductsController::class, 'show'])->name('company_product.show');
    // Route::get('/edit/{id}', [CompanyProductsController::class, 'edit'])->name('company_product.edit');
    // Route::get('/create', [CompanyProductsController::class, 'create'])->name('company_product.create');
    // Route::post('/store', [CompanyProductsController::class, 'store'])->name('company_product.store');
    // Route::post('/update/{id}', [CompanyProductsController::class, 'update'])->name('company_product.update');
});

Route::group(['middleware' => ['auth:sanctum', 'is_auth']], function () {
    Route::post('personal-information', [UsersController::class, 'setPersonalInformation']);
    Route::get('personal-information', [UsersController::class, 'getPersonalInformation']);
    Route::post('set-address', [UsersController::class, 'setAddress']);

});

