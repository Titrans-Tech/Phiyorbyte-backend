<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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
Route::group(['prefix' => 'admin'], function () {
  Route::post('/registeradmin', [AdminController::class, 'registeradmin']);
  Route::post('/loginadmin', [AdminController::class, 'loginadmin']);
  Route::post('/logoutadmin', [AdminController::class, 'logoutadmin']);

});


Route::post('registeruser',[AuthController::class,'registeruser']);
Route::post('loginuser',[AuthController::class,'loginuser']);
Route::post('logout',[AuthController::class,'logout'])
  ->middleware('auth:sanctum');
