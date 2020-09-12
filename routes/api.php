<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Product
Route::resource('products', 'Panel\Product\ProductController');
Route::resource('products.carts', 'Product\ProductCartController')->only(['store', 'destroy','update']);

//Category
Route::resource('categories', 'Panel\Category\CategoryController')->except(['show']);

Route::get('/', 'MainController@index')->name('main');

//Cart
Route::get('show.cart','Cart\CartController@showCart');
Route::resource('carts', 'Cart\CartController')->only(['index']);

Route::resource('subscriber', 'Subscriber\SubscriberController')->only(['store']);


//Send by email offer
Route::post('send', 'Panel\SendEmail\SendEmailOffer@sendEmail');
