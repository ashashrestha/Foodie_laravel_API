<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RestaurantTypeController;


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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/show-type-table', [RestaurantTypeController::class, 'showTypeTable'])->name('show_type_table');


// RestaurantType
Route::get('/dashboard/restaurantType', [RestaurantTypeController::class, 'index'])->name('show_type_table');
Route::get('/dashboard/restaurantType/create', [RestaurantTypeController::class, 'create']);
Route::post('/dashboard/restaurantType/store', [RestaurantTypeController::class, 'store']);
Route::get('/dashboard/restaurantType/edit/{id}', [RestaurantTypeController::class, 'edit']);
Route::put('/dashboard/restaurantType/{id}/update', [RestaurantTypeController::class, 'update']);
Route::delete('/dashboard/restaurantType/{id}', [RestaurantTypeController::class, 'destroy'])->name('delete_restaurant_type');




