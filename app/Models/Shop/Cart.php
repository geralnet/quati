<?php

namespace App\Models\Shop;

/**
 * Class Cart
 */
class Cart {
    public static function get() : Cart {
        $cart = session('cart');
        if (is_null($cart)) {
            $cart = new Cart();
            session(['cart' => $cart]);
        }
        return $cart;
    }

    private function __construct() { }

    /** @var int[] */
    private $products = [];

    public function addProduct(int $productId, int $quantity) {
        if (!array_key_exists($productId, $this->products)) {
            $this->products[$productId] = 0;
        }
        $this->products[$productId] += $quantity;
    }

    public function getProductQuantity($productId) {
        if (!array_key_exists($productId, $this->products)) {
            return 0;
        }
        return $this->products[$productId];
    }

    public function getProductsQuantities() {
        return $this->products;
    }
}
