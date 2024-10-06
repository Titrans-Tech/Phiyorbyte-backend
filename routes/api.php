<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProductcolorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsizeController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;

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
Route::get('viewcategories/{categoryname}',[ProductController::class,'viewallcategories']);
Route::get('category/mencategory',[ProductController::class,'viewmencategory']);
Route::get('category/womencategory',[ProductController::class,'womencategory']);
Route::get('category/newarrivalcategory',[ProductController::class,'newarrivals']);

Route::get('/product/subcategory/{name}', [ProductController::class, 'subcategoryproducts']);
Route::post('/cart/add/{id}', [CartController::class, 'addProductToCart']);
Route::post('/cart/couponapplication', [CartController::class, 'applyCoupon']);
Route::get('/cart/deleteartproduct/{id}', [CartController::class, 'remove']);
Route::post('/cart/addcheckout', [CartController::class, 'checkout']);


Route::post('/favorite/addfavorite/{id}', [FavoriteController::class, 'addProductTofavorite']);
Route::post('/favorite/addcoupon', [FavoriteController::class, 'applyCoupon']);
Route::post('/favorite/addcheckout', [FavoriteController::class, 'checkout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/admin/coupon/editcoupon/{id}', [CouponController::class, 'update']);
    Route::get('/admin/coupon/deletecoupon/{id}', [CouponController::class, 'destroy']);
    Route::post('/admin/coupon/createcouponcode', [CouponController::class, 'store']);
    Route::get('/admin/coupon/viewcoupon', [CouponController::class, 'viewcoupon']);
    
    Route::post('/admin/subcategory/createsubcategory', [SubcategoryController::class, 'store']);
    Route::put('/admin/subcategory/editsubcategory/{id}', [SubcategoryController::class, 'update']);
    Route::get('/admin/subcategory/deletsubcategory/{id}', [SubcategoryController::class, 'destroy']);
    Route::get('/admin/viewusers', [UserController::class, 'index']);
    Route::get('/admin/suspenduser/{ref_no}', [UserController::class, 'suspenduser']);
    Route::get('/admin/approveduser/{ref_no}', [UserController::class, 'approveduser']);
    Route::patch('/admin/users/{ref_no}', [UserController::class, 'update']);
    Route::delete('/admin/deleteuser/{ref_no}', [UserController::class, 'destroy']);
    Route::get('/admin/profile/{ref_no}', [UserController::class, 'profile']);
    Route::get('/admin/profile/{ref_no}', [UserController::class, 'profile']);
    
    Route::post('/admin/productsize/createsizes', [ProductsizeController::class, 'createsize']);
    Route::put('/admin/productsize/editsize/{id}', [ProductsizeController::class, 'update']);
    Route::get('/admin/productsize/deletesize/{id}', [ProductsizeController::class, 'destroysize']);
    Route::get('/admin/productsize/viewsizes', [ProductsizeController::class, 'viewsize']);
    
    
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
  Route::post('/logoutadmin', [AdminController::class, 'logoutadmin']);

});




Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/users/viewmyprofile/{ref_no}', [UserController::class, 'myprofile']);
  Route::put('/users/changepasword/{ref_no}', [AuthController::class, 'changelogindetails']);
  Route::get('/users/approveduser/{ref_no}', [UserController::class, 'approveduser']);
  Route::patch('/users/users/{ref_no}', [UserController::class, 'update']);
  Route::delete('/users/deleteuser/{ref_no}', [UserController::class, 'destroy']);
  Route::get('/users/profile/{ref_no}', [UserController::class, 'profile']);
  

  Route::post('/users/products/createproducts', [ProductController::class, 'createproduct']);
  Route::put('/users/products/editproducts/{id}', [ProductController::class, 'updateproduct']);
  Route::get('/users/products/viewproducts', [ProductController::class, 'viewproduct']);
  Route::get('/users/products/viewsingleproduct/{ref_no}', [ProductController::class, 'show']);
  Route::get('/users/products/deleteproduct/{ref_no}', [ProductController::class, 'destroy']);
  Route::get('/users/products/markavailable/{id}', [ProductController::class, 'productavailable']);
  Route::get('/users/products/markunavailable/{id}', [ProductController::class, 'productunavailable']);
  
 
 
});



Route::post('registeruser',[AuthController::class,'registeruser']);
Route::post('loginuser',[AuthController::class,'loginuser']);
Route::post('logout',[AuthController::class,'logout'])
  ->middleware('auth:sanctum');
