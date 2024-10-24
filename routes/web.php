<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use Illuminate\Support\Facades\Artisan;
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

//phiyo  A[lyBHI)JrP6


Route::get('/api-endpoints', function () {
    // Run the 'route:list' command to get all routes
    Artisan::call('route:list --json');
    $routes = json_decode(Artisan::output(), true);

    // Filter API routes (assuming your API routes are under 'api/' prefix)
    $apiRoutes = array_filter($routes, function ($route) {
        return strpos($route['uri'], 'api/') === 0;
    });

    // Pass the filtered API routes to the view
    return view('api-endpoints', ['routes' => $apiRoutes]);
});

Route::get('/', function () {
    return view('welcome');
});




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
