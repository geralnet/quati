<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Cart;
use App\Models\Shop\Product;
use Tests\Unit\TestCase;

/**
 * Class CartTest
 */
class CartTest extends TestCase {
    /** @test */
    public function it_can_hold_a_product_2_times_a_product() {
        $product = factory(Product::class)->make(['id' => 1]);

        $cart = Cart::get();
        $cart->addProduct($product->id, 2);

        self::assertSame(2, $cart->getProductQuantity(1));
    }

    /** @test */
    public function it_can_provide_all_products() {
        $product1 = factory(Product::class)->make(['id' => 1]);
        $product2 = factory(Product::class)->make(['id' => 2]);

        $cart = Cart::get();
        $cart->addProduct($product1->id, 10);
        $cart->addProduct($product2->id, 20);

        self::assertSame([1 => 10, 2 => 20], $cart->getProductsQuantities());
    }

    /** @test */
    public function it_can_remove_products() {
        $product = factory(Product::class)->make(['id' => 1]);

        $cart = Cart::get();
        $cart->addProduct($product->id, 10);
        $cart->removeProduct($product->id);

        self::assertEmpty($cart->getProductsQuantities());
    }

    /** @test */
    public function it_can_remove_all_products() {
        $product = factory(Product::class)->make(['id' => 1]);

        $cart = Cart::get();
        $cart->addProduct($product->id, 10);

        $cart->removeAll();

        self::assertEmpty($cart->getProductsQuantities());
    }

    /** @test */
    public function it_can_set_the_quantity_of_a_product() {
        $product = factory(Product::class)->make(['id' => 1]);

        $cart = Cart::get();
        $cart->addProduct($product->id, 10);
        $cart->setProduct($product->id, 2);

        self::assertSame([1 => 2], $cart->getProductsQuantities());
    }

    /** @test */
    public function it_exists() {
        self::assertNotNull(Cart::get());
    }

    /** @test */
    public function it_is_stored_in_the_session() {
        $product = factory(Product::class)->make(['id' => 1]);

        $cart = Cart::get();
        $cart->addProduct($product->id, 2);
        session(['cart' => $cart]);

        $cart = session('cart');
        self::assertNotNull($cart);
        self::assertSame(2, $cart->getProductQuantity(1));
    }
}
