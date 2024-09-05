<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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
