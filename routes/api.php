<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductcolorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsizeController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
// Route::post('password/reset', [PasswordResetController::class, 'reset']);


Route::get('/payment', [OrdersController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment/process', [OrdersController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/callback', [OrdersController::class, 'paymentCallback'])->name('payment.callback');

Route::get('/payment/success', function () {
    return 'Payment successful';
})->name('payment.success');

Route::get('/payment/failed', function () {
    return 'Payment failed';
})->name('payment.failed');
// Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
// Route::post('password/reset', [PasswordResetController::class, 'reset']);


Route::get('viewcategories/{categoryname}',[ProductController::class,'viewallcategories']);
Route::get('category/mencategory',[ProductController::class,'viewmencategory']);
Route::get('category/womencategory',[ProductController::class,'womencategory']);
Route::get('category/newarrivalcategory',[ProductController::class,'newarrivals']);
Route::get('subcategory/mensubcategory',[SubcategoryController::class,'viewmensubcategory']);
Route::get('subcategory/womensubcategory',[SubcategoryController::class,'viewwomensubcategory']);

Route::get('product/displayallproducts',[ProductController::class,'displayallproduct']);

Route::get('/product/subcategory/{name}', [SubcategoryController::class, 'subcategoryproducts']);

Route::get('/thankyou', [OrdersController::class, 'thankyou']);
Route::get('/firstphoto/{ref_no}', [ProductController::class, 'firstphoto']);




Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/admin/coupon/editcoupon/{id}', [CouponController::class, 'update']);
    Route::get('/admin/coupon/deletecoupon/{id}', [CouponController::class, 'destroy']);
    Route::post('/admin/coupon/createcouponcode', [CouponController::class, 'store']);
    Route::get('/admin/coupon/viewcoupon', [CouponController::class, 'viewcoupon']);
    Route::get('/admin/orders/vieworders', [OrdersController::class, 'vieworder']);
    Route::post('/cart/addcheckout', [CartController::class, 'checkout']);
    Route::get('/admin/subcategory/viewsubcategories', [SubcategoryController::class, 'show']);
    Route::post('/admin/subcategory/createsubcategory', [SubcategoryController::class, 'store']);
    Route::put('/admin/subcategory/editsubcategory/{id}', [SubcategoryController::class, 'update']);
    Route::get('/admin/subcategory/deletsubcategory/{id}', [SubcategoryController::class, 'destroy']);
    Route::get('/admin/viewusers', [UserController::class, 'index']);
    Route::get('/admin/suspenduser/{ref_no}', [UserController::class, 'suspenduser']);
    Route::get('/admin/approveduser/{ref_no}', [UserController::class, 'approveduser']);
    Route::patch('/admin/users/{ref_no}', [UserController::class, 'update']);
    Route::delete('/admin/deleteuser/{ref_no}', [UserController::class, 'destroy']);
    Route::get('/admin/profile/{ref_no}', [UserController::class, 'profile']);
    Route::put('/admin/users/changepassword/{ref_no}', [UserController::class, 'updatepassword']);
    
    Route::post('/admin/productsize/createsizes', [ProductsizeController::class, 'createsize']);
    Route::put('/admin/productsize/editsize/{id}', [ProductsizeController::class, 'update']);
    Route::get('/admin/productsize/deletesize/{id}', [ProductsizeController::class, 'destroysize']);
    Route::get('/admin/productsize/viewsizes', [ProductsizeController::class, 'viewsize']);
    // favorite/addfavorite/14
    
    Route::post('/admin/products/createproducts', [ProductController::class, 'createproduct']);
    Route::put('/admin/products/editproducts/{id}', [ProductController::class, 'updateproduct']);
    Route::get('/admin/products/viewproducts', [ProductController::class, 'viewproduct']);
    Route::get('/admin/products/viewsingleproduct/{ref_no}', [ProductController::class, 'show']);
    Route::get('/admin/products/deleteproduct/{ref_no}', [ProductController::class, 'destroy']);
    Route::get('/admin/products/markavailable/{id}', [ProductController::class, 'productavailable']);
    Route::get('/admin/products/markunavailable/{id}', [ProductController::class, 'productunavailable']);
    
    Route::post('/admin/categories/createcategory', [CategoryController::class, 'createcategory']);
    Route::get('/admin/categories/viewcategory', [CategoryController::class, 'show']);
    Route::get('/admin/categories/deletecategory/{id}', [CategoryController::class, 'destroy']);
    Route::put('/admin/categories/editcategory/{id}', [CategoryController::class, 'update']);
    
    
    Route::get('/admin/colors/viewcolors', [ProductcolorController::class, 'viewcolor']);
    Route::get('/admin/colors/destroycolors/{id}', [ProductcolorController::class, 'destroycolor']);
    Route::put('/admin/colors/editcolors/{id}', [ProductcolorController::class, 'updatecolor']);
    Route::post('/admin/colors/createcolors', [ProductcolorController::class, 'createcolor']);
});

Route::group(['prefix' => 'admin'], function () {
  Route::post('/registeradmin', [AdminController::class, 'registeradmin']);
  Route::post('/loginadmin', [AdminController::class, 'loginadmin']);
  Route::get('/logoutadmin', [AdminController::class, 'logoutadmin']);

});


Route::get('/product/productdetails/{ref_no}', [ProductController::class, 'productdetail']);

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/users/cart/viewmycartitems', [CartController::class, 'mycartproducts']);

  Route::get('/users/viewmyprofile/{ref_no}', [UserController::class, 'myprofile']);
  Route::put('/users/changepasword/{ref_no}', [AuthController::class, 'changelogindetails']);
  Route::get('/users/approveduser/{ref_no}', [UserController::class, 'approveduser']);
  Route::patch('/users/users/{ref_no}', [UserController::class, 'update']);
  Route::delete('/users/deleteuser/{ref_no}', [UserController::class, 'destroy']);
  Route::get('/users/profile/{ref_no}', [UserController::class, 'profile']);
  Route::get('/favorite/myfavourite', [FavoriteController::class, 'myfavourites']);
  
  // myordersproduct obodobright0@gmail.com
  
  Route::get('/users/order/viewmyorders', [OrdersController::class, 'viewmyorder']);
  Route::get('/users/cart/myordersproduct', [OrdersController::class, 'myordersproducts']);
  Route::get('/users/cart/orderdetails/{id}', [OrdersController::class, 'ordermydetail']);
 

  Route::post('/carts/add/{id}', [CartController::class, 'addProductToCart']);
  Route::post('/cart/couponapplication', [CartController::class, 'applyCoupon']);
  Route::get('/cart/deleteartproduct/{id}', [CartController::class, 'remove']);



  Route::post('/favorite/addfavorite/{id}', [FavoriteController::class, 'addProductTofavorite']);
  Route::post('/favorite/addcoupon', [FavoriteController::class, 'applyCoupon']);
  Route::post('/favorite/addcheckout', [FavoriteController::class, 'checkout']);
});



Route::post('registeruser',[AuthController::class,'registeruser']);
Route::post('loginuser',[AuthController::class,'loginuser']);


// Route::post('/users/forgot-password', [UserController::class, 'forgotPassword']);
// Route::post('/users/reset-password', [UserController::class, 'resetPassword']);

// Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');




// Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
// Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
// Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
// Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');



// Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
// Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
// Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
// Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');


// Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
// Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store']);
// Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');

Route::post('users/logoutuser',[AuthController::class,'logout'])
  ->middleware('auth:sanctum');
