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

Route::get('@cart', 'ShopController@getCart');

Route::put('@cart', 'ShopController@putCart');
