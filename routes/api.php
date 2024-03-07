<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\HeaderController;
use App\Http\Controllers\Api\WarehouseApiController;
use \App\Http\Controllers\Company\WarehouseController;
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
Route::get('get-company-products', [AddressController::class, 'getCompanyProducts']);
Route::get('get-districts', [AddressController::class, 'getCities']);
Route::get('get-image', [HeaderController::class, 'getImage']);
Route::post('phone-verify', [AuthController::class, 'PhoneVerify']);
Route::post('phone-register', [AuthController::class, 'PhoneRegister']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/googleauth', [AuthController::class, 'redirectGoogle'])->name('redirectGoogle');
Route::get('/googleauth/callback', [AuthController::class, 'callbackGoogle'])->name('callbackGoogle');
Route::get('/googleauth/user', [AuthController::class, 'responseUser'])->name('responseUser');
Route::get('get-advertising', [HeaderController::class, 'getAdvertising']);

Route::group(['prefix' => 'product'], function () {
    Route::get('/list', [ProductController::class, 'index']);
    Route::get('/show/warehouse_product', [ProductController::class, 'show']);
});

// Route::get('get-categories-by-product/{id}', [ProductController::class, 'getCategoriesByProduct'])->name('get_categories_by_product');
Route::get('get-banner', [BannerController::class, 'getBanner']);
// Route::get('get-products-by-categories', [CategoryController::class, 'getProductsByCategories']);
Route::get('get-products-by-category', [CategoryController::class, 'getProductsByCategory']);
Route::get('get-categories', [CategoryController::class, 'getCategories']);
Route::get('get-warehouses', [ProductController::class, 'getWarehouses']);
Route::get('profile-info', [CategoryController::class, 'profileInfo']);
Route::get('product', [ProductController::class, 'getProduct']);
Route::get('/anime-category-size-color', [OrderController::class, 'AnimeCategorySizeColor']);
Route::post('send-code', [UsersController::class, 'sendCode']);
Route::post('verify-token', [UsersController::class, 'verifyToken']);
Route::post('password-reset', [UsersController::class, 'passwordReset']);

Route::group(['middleware' => ['auth:sanctum', 'is_auth']], function () {
    Route::get('user-info', [CategoryController::class, 'userInfo']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('personal-information', [UsersController::class, 'setPersonalInformation']);
    Route::get('personal-information', [UsersController::class, 'getPersonalInformation']);
    Route::post('set-address', [AddressController::class, 'setAddress']);
    Route::post('edit-address', [AddressController::class, 'editAddress']);
    Route::get('get-address', [AddressController::class, 'getAddress']);
    Route::delete('destroy-address', [AddressController::class, 'destroy']);
    Route::get('get-cards', [CardController::class, 'getCards']);
    Route::post('store-card', [CardController::class, 'storeCard']);
    Route::post('update-card', [CardController::class, 'updateCard']);
    Route::get('show-card', [CardController::class, 'showCard']);
    Route::post('destroy-card', [CardController::class, 'destroyCard']);
    Route::get('pick-up-point', [AddressController::class, 'pickUpFunction']);
    Route::post('print-store', [WarehouseApiController::class, 'store']);
    Route::post('print-update', [WarehouseApiController::class, 'update']);
    Route::group(['prefix' => 'order'], function () {
        Route::post('/set-warehouse', [OrderController::class, 'setWarehouse']);
        Route::post('/add-coupon', [OrderController::class, 'addCoupon']);
        Route::post('connection/to_order', [OrderController::class, 'connectOrder']);
        Route::post('accepted/order', [OrderController::class, 'acceptedOrder']);
        Route::post('delete/order-detail', [OrderController::class, 'deleteOrderDetail']);
        Route::get('/get-basket', [OrderController::class, 'getBasket']);
        Route::get('/get-order', [OrderController::class, 'getOrder']);
        Route::get('/get-my-orders', [OrderController::class, 'getMyOrders']);
        Route::get('/perform-order', [OrderController::class, 'performOrder'])->name('order.perform');
        Route::get('/get-order-detail-by-order-id', [OrderController::class, 'getOrderDetailByOrderId']);
    });
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::post('delete-carousel', [BannerController::class, 'deleteCarousel']);
Route::post('delete-product', [ProductController::class, 'deleteProductImage']);
Route::post('delete-warehouse', [WarehouseController::class, 'deleteWarehouseImage']);
