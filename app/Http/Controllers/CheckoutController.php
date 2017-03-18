<?php

namespace App\Http\Controllers;

use App\Models\Shop\Cart;

class CheckoutController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function getIndex() {
        return redirect('/@checkout/address');
    }

    public function getAddress() {
        $prices = Cart::get()->getCalculatePrices();
        $items = $prices['products'];
        $totalPrice = $prices['total'];
        return view('checkout.address', compact('totalPrice', 'items'));
    }

    public function postAddress() {
        return redirect('/@checkout/payment');
    }

    public function getPayment() {
        return view('checkout.payment');
    }

    public function postPayment() {
        return redirect('/@checkout/confirmation');
    }

    public function getConfirmation() {
        return view('checkout.confirmation');
    }
}
