<?php

namespace App\Models\Shop;

use Auth;

class Cart {
    public static function get() : Cart {
        $sessionCart = session('cart');
        if (Auth::user()) {
            $cart = new Cart();
            $cart->load();
            if (!is_null($sessionCart)) {
                $cart->import($sessionCart);
                session(['cart' => null]);
            }
        }
        else {
            if (is_null($sessionCart)) {
                $cart = new Cart();
                session(['cart' => $cart]);
            }
            else {
                $cart = $sessionCart;
            }
        }
        return $cart;
    }

    /** @var CartProduct[] */
    private $products = [];

    public function addProduct(int $productId, int $quantity) {
        $this->ensureProductExists($productId);
        $this->products[$productId]->addQuantity($quantity);
    }

    public function getProductQuantity($productId) {
        if (!array_key_exists($productId, $this->products)) {
            return 0;
        }
        return $this->products[$productId]->quantity;
    }

    public function getProductsQuantities() {
        $quantities = [];
        foreach ($this->products as $id => $product) {
            $quantities[$product->product_id] = $product->quantity;
        }
        return $quantities;
    }

    public function removeAll() {
        $this->products = [];
    }

    public function removeProduct($productId) {
        $this->setProduct($productId, 0);
    }

    public function setProduct($productId, $quantity) {
        if (($quantity == 0) && !array_key_exists($productId, $this->products)) {
            return;
        }

        $this->ensureProductExists($productId);
        $this->products[$productId]->setQuantity($quantity);

        if ($quantity == 0) {
            unset($this->products[$productId]);
        }
    }

    private function ensureProductExists(int $productId) {
        if (array_key_exists($productId, $this->products)) {
            return;
        }

        $userId = is_null(Auth::user()) ? null : Auth::user()->id;
        $product = new CartProduct();
        $product->forceFill([
            'user_id'    => $userId,
            'product_id' => $productId,
            'quantity'   => 0,
        ]);
        $this->products[$productId] = $product;
    }

    private function import(Cart $cart) {
        foreach ($cart->getProductsQuantities() as $productId => $quantity) {
            $this->addProduct($productId, $quantity);
        }
    }

    private function load() {
        $cartProducts = CartProduct::where(['user_id' => Auth::user()->id])->get();
        foreach ($cartProducts as $product) {
            $this->products[$product->product_id] = $product;
        }
    }
}
