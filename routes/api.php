<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\EmailVerificationController;

use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\Restaurant\RestaurantTypeController;
use App\Http\Controllers\Api\Restaurant\HomeRestaurantController;
use App\Http\Controllers\Api\Restaurant\RestaurantController;
use App\Http\Controllers\Api\Restaurant\CategoryController;
use App\Http\Controllers\Api\Restaurant\MenuController;
use App\Http\Controllers\Api\Restaurant\CartController;
use App\Http\Controllers\Api\Restaurant\PaymentController;
use App\Http\Controllers\Api\Restaurant\HomePageController;
use App\Http\Controllers\Api\Restaurant\BestfoodController;
use App\Http\Controllers\Api\Restaurant\BestOfferController;
use App\Http\Controllers\Api\Restaurant\ImageSliderController;
use App\Http\Controllers\Api\Restaurant\ProfileUserController;

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

    // Authentication 
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('password/forget-password', [ForgetPasswordController::class, 'forgetPassword']);
    Route::post('password/reset', [ResetPasswordController::class, 'passwordReset']);


    // Email verification routes
    Route::post('email-verification', [EmailVerificationController::class, 'email_verification']);
    Route::get('email-verification', [EmailVerificationController::class, 'sendEmailverification']);

     //Best Food
     Route::get('/bestFood/index', [BestfoodController::class, 'index']);
     Route::post('/bestFood/store', [BestfoodController::class, 'store']);
     Route::post('/bestFood/{id}/update', [BestfoodController::class, 'update']);
     Route::delete('/bestFood/{id}/delete', [BestfoodController::class, 'destroy']);

     Route::get('/getslider',[ImageSliderController::class,'index']);
     Route::post('/slider',[ImageSliderController::class,'store']);

    
    // Homepage Content
    Route::get('/homepage/index', [HomePageController::class, 'index']);
    Route::post('/homepage/{containerNumber}/store', [HomePageController::class, 'store']);
    Route::post('/homepage/{containerNumber}/update', [HomePageController::class, 'update']);


    // Restaurant Types
    Route::get('/restaurantType/index', [RestaurantTypeController::class, 'index']);
    Route::post('/restaurantType/store', [RestaurantTypeController::class, 'store']);
    Route::get('/restaurantType/{id}/', [RestaurantTypeController::class, 'showById']);  
    Route::get('/restaurantType/{id}/restaurants', [RestaurantTypeController::class, 'showRestaurantbyType']); 
    Route::post('/restaurantType/{id}/update', [RestaurantTypeController::class, 'update']);
    Route::delete('/restaurantType/{id}/delete', [RestaurantTypeController::class, 'destroy']);

    // Restaurant Types
    Route::get('/homeRestaurant/index', [HomeRestaurantController::class, 'index']);
    Route::post('/homeRestaurant/store', [HomeRestaurantController::class, 'store']); 
    Route::post('/homeRestaurant/{id}/update', [HomeRestaurantController::class, 'update']);
    Route::delete('/homeRestaurant/{id}/delete', [HomeRestaurantController::class, 'destroy']);


    //Best Offers
    Route::get('/bestOffer/index', [BestOfferController::class, 'index']);
    Route::post('/bestOffer/store', [BestOfferController::class, 'store']);
    Route::post('/bestOffer/{id}/update', [BestOfferController::class, 'update']);
    

    // Restaurants
    Route::get('/restaurant/index', [RestaurantController::class, 'index']);
    Route::post('/restaurant/store', [RestaurantController::class, 'store']);
    Route::get('/restaurant/{id}/', [RestaurantController::class, 'showById']);   
    Route::get('/restaurant/{id}/restaurants', [RestaurantController::class, 'showRestaurant']); 
    Route::post('/restaurant/{id}/update', [RestaurantController::class, 'update']);
    Route::delete('/restaurant/{id}/delete', [RestaurantController::class, 'destroy']);
 
    
    // Categories
    Route::get('/category/index', [CategoryController::class, 'index']);
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::get('/category/{id}/', [CategoryController::class, 'showById']);   
    Route::post('/category/{id}/update', [CategoryController::class, 'update']);
    Route::delete('/category/{id}/delete', [CategoryController::class, 'destroy']);

    // Menus
    Route::get('/menu/index', [MenuController::class, 'index']);
    Route::post('/menu/store', [MenuController::class, 'store']);
    Route::get('/menu/{id}/', [MenuController::class, 'showById']);   
    Route::get('/menu/{id}/details', [MenuController::class, 'showMenuWithDetails']);   
    Route::post('/menu/{id}/update', [MenuController::class, 'update']);
    Route::get('/menu/search/&query', [MenuController::class,'search']);
    Route::delete('/menu/{id}/delete', [MenuController::class, 'destroy']);
   
        // Protected routes requiring authentication
    Route::middleware('auth:api')->group(function () {
        
    // User information route (middle of protected routes)
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    //profile
    Route::get('profile/{id}', [ProfileUserController::class, 'show']);
    Route::post('profile/{id}', [ProfileUserController::class, 'update']);


    //Cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/{menuId}', [CartController::class, 'addToCart']);
    Route::post('cart/{cartItemId}', [CartController::class, 'updateCartItem']);
    Route::delete('cart/{cartItemId}', [CartController::class, 'removeCartItem']);

    // Payment
    Route::post('payments', [PaymentController::class, 'createPayment']);

  
    // Logout route
    Route::post('logout', [LoginController::class, 'logout']);
    });