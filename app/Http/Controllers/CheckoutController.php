<?php

namespace App\Http\Controllers;

use App\Models\Shop\Cart;

class CheckoutController extends Controller {
    public function getIndex() {
        $prices = Cart::get()->getCalculatePrices();
        $items = $prices['products'];
        $totalPrice = $prices['total'];
        return view('checkout.index', compact('totalPrice', 'items'));
    }
}
