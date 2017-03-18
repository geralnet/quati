<?php

namespace App\Http\Controllers;

use App\Models\Shop\Cart;
use Auth;

class CheckoutController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function getIndex() {
        $prices = Cart::get()->getCalculatePrices();
        $items = $prices['products'];
        $totalPrice = $prices['total'];
        return view('checkout.index', compact('totalPrice', 'items'));
    }
}
