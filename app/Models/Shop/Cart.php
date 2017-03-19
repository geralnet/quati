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

    /** @var CartItem[] */
    private $items = [];

    public function addProduct(int $productId, int $quantity) {
        $this->ensureProductExists($productId);
        $this->items[$productId]->addQuantity($quantity);
    }

    public function getCalculatePrices() {
        $items = [];
        $totalPrice = 0;
        foreach ($this->items as $item) {
            $quantity = $item->quantity;
            $product = Product::find($item->product_id);
            $subtotal = ($quantity * $product->price);
            $items[] = [
                'quantity' => $quantity,
                'product'  => $product,
                'subtotal' => $subtotal,
            ];
            $totalPrice += $subtotal;
        }
        return [
            'total'    => $totalPrice,
            'products' => $items,
        ];
    }

    public function getProductQuantity($productId) {
        if (!array_key_exists($productId, $this->items)) {
            return 0;
        }
        return $this->items[$productId]->quantity;
    }

    public function getProductsQuantities() {
        $quantities = [];
        foreach ($this->items as $id => $product) {
            $quantities[$product->product_id] = $product->quantity;
        }
        return $quantities;
    }

    public function removeAll() {
        $this->items = [];
    }

    public function removeProduct($productId) {
        $this->setProduct($productId, 0);
    }

    public function setProduct($productId, $quantity) {
        if (($quantity == 0) && !array_key_exists($productId, $this->items)) {
            return;
        }

        $this->ensureProductExists($productId);
        $this->items[$productId]->setQuantity($quantity);

        if ($quantity == 0) {
            unset($this->items[$productId]);
        }
    }

    private function ensureProductExists(int $productId) {
        if (array_key_exists($productId, $this->items)) {
            return;
        }

        $userId = is_null(Auth::user()) ? null : Auth::user()->id;
        $item = new CartItem();
        $item->forceFill([
            'user_id'    => $userId,
            'product_id' => $productId,
            'quantity'   => 0,
        ]);
        $this->items[$productId] = $item;
    }

    private function import(Cart $cart) {
        foreach ($cart->getProductsQuantities() as $productId => $quantity) {
            $this->addProduct($productId, $quantity);
        }
    }

    private function load() {
        $cartProducts = CartItem::where(['user_id' => Auth::user()->id])->get();
        foreach ($cartProducts as $product) {
            $this->items[$product->product_id] = $product;
        }
    }
}
