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

use App\Models\Shop\Product;

Route::get('/{shop_path?}', 'ShopController@getShop')
     ->where('shop_path', '(?!@).*'); // Matches anything that does not start with '@'.

Route::get('@cart', function() {
    return 'Shopping Cart';
});

Route::put('@cart', function(\Illuminate\Http\Request $request) {
    $quantities = $request->input('quantities');

    $html = '<p>Shopping Cart</p>';
    $total = 0;
    foreach ($quantities as $id => $quantity) {
        $quantity = (int)$quantity;
        if ($quantity < 0) {
            abort(400, 'Invalid product quantity.');
        }
        if ($quantity == 0) {
            continue;
        }
        $product = Product::find($id);
        $subtotal = $quantity * $product->price;
        $total += $subtotal;

        $html .= <<<HTML
<b>Product: </b> {$product->name}<br />
<b>Price: </b> {$product->price}<br />
<b>Quantity: </b> {$quantity}<br />
<b>Subtotal: </b>$ {$subtotal}<br />
<br />
HTML;
    }

    $html .= sprintf('<br /><b>Total: </b> $ %0.2f', $total);

    return $html;
});
