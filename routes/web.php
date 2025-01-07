<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SessionController;

/* Route::get('/', function () {
    return view('welcome');
}); */
/* Route::get('/','ProductController@getHomePageProducts')->name('home'); */
Route::get('/', [ProductController::class,'getHomePageProducts'])->name('home');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');
Route::post('/savesession', [SessionController::class, 'saveSesssion'])->name('session.save');
Route::post('/logout',function () {
    session()->forget('user_data');
    return redirect()->route('home');
})->name('logout');

Route::get('shop','App\Http\Controllers\ProductController@getProducts')->name('shop');

Route::get('cart','App\Http\Controllers\ProductController@getCart')->name('cart');

Route::get('address',function () {
    return view('address');
})->name('user.address');

Route::get('address/{id}','App\Http\Controllers\AddressController@get')->name('address.get');

Route::post('address','App\Http\Controllers\AddressController@store')->name('address.store');

Route::post('addressupdate','App\Http\Controllers\AddressController@update')->name('address.update');

Route::get('product/{id}','App\Http\Controllers\ProductController@getSingleProduct')->name('product');

Route::get('product/category/{id}','App\Http\Controllers\ProductController@getProductsByCategory')->name('category.products');

Route::get('product/addToCart/{id}','App\Http\Controllers\ProductController@addToCart')->name('product.addToCart');

Route::get('product/removeFromCart/{id}','App\Http\Controllers\ProductController@removeFromCart')->name('product.removeFromCart');

Route::get('/checkout', function () {
    $userData = session('user_data');
    return view('checkout',['data'=>$userData]);
})->name('checkout');

Route::get('/contact', function () {
    $userData = session('user_data');
    return view('contact',['data'=>$userData]);
})->name('contact');

Route::get('/about', function () {
    $userData = session('user_data');
    return view('about', ['data'=>$userData]);
})->name('about');

Route::post('checkout','App\Http\Controllers\PaymentController@createRequest')->name('createPaymentRequest');

Route::get('redirect/payment/','App\Http\Controllers\PaymentController@getAcknowledgement')->name('payment.successful');



