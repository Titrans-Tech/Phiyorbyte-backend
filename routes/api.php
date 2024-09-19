<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductcolorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsizeController;
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

Route::middleware(['auth:sanctum'])->group(function () {
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

// Route::middleware('auth:api')->group(function () {
  
//   // Other protected routes
// });
// Route::group(['middleware' => 'auth:api'], function () {
    
//     Route::get('/orders', [OrderController::class, 'index']);
//     Route::post('/orders', [OrderController::class, 'store']);
//     Route::get('/orders/{id}', [OrderController::class, 'show']);
//     Route::put('/orders/{id}', [OrderController::class, 'update']);
//     Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
// });

Route::post('registeruser',[AuthController::class,'registeruser']);
Route::post('loginuser',[AuthController::class,'loginuser']);
Route::post('logout',[AuthController::class,'logout'])
  ->middleware('auth:sanctum');
