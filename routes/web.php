<?php
declare(strict_types = 1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('{shop_path?}', 'ShopController@getShop')
     ->where('shop_path', '(?!@).*'); // Matches anything that does not start with '@'.

// Cart Routes ...
Route::get('@cart', 'ShopController@getCart');
Route::put('@cart', 'ShopController@putCart');

// Checkout Routes ...
Route::get('@checkout', 'CheckoutController@getIndex');
Route::get('@checkout/address', 'CheckoutController@getAddress');
Route::post('@checkout/address', 'CheckoutController@postAddress');
Route::get('@checkout/payment', 'CheckoutController@getPayment');
Route::post('@checkout/payment', 'CheckoutController@postPayment');
Route::get('@checkout/confirmation', 'CheckoutController@getConfirmation');

// Authentication Routes...
$this->get('@auth/signin', 'Auth\LoginController@showLoginForm');
$this->post('@auth/signin', 'Auth\LoginController@login');
$this->post('@auth/signout', 'Auth\LoginController@logout');

// Registration Routes...
$this->get('@auth/signup', 'Auth\RegisterController@showRegistrationForm');
$this->post('@auth/signup', 'Auth\RegisterController@register');
