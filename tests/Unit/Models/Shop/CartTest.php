<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\Cart;
use App\Models\Shop\CartProduct;
use App\Models\Shop\Product;
use App\User;
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
    public function it_can_remove_all_products() {
        $product = factory(Product::class)->make(['id' => 1]);

        $cart = Cart::get();
        $cart->addProduct($product->id, 10);

        $cart->removeAll();

        self::assertEmpty($cart->getProductsQuantities());
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
    public function it_can_restore_from_the_database() {
        $user = factory(User::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        $this->be($user);
        $cart = Cart::get();
        $cart->addProduct($product1->id, 5);
        $cart->addProduct($product2->id, 7);

        $cart = Cart::get();
        $cartProducts = $cart->getProductsQuantities();
        self::assertCount(2, $cartProducts);

        self::assertSame(5, $cartProducts[$product1->id]);
        self::assertSame(7, $cartProducts[$product2->id]);
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
    public function it_is_not_stored_in_the_session_for_authenticated_users() {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create(['id' => 1]);

        $this->be($user);

        $cart = Cart::get();
        $cart->addProduct($product->id, 2);

        self::assertNull(session('cart'));
    }

    /** @test */
    public function it_is_stored_in_the_database_for_authenticated_users() {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create(['id' => 1]);

        $this->be($user);

        $cart = Cart::get();
        $cart->addProduct($product->id, 2);

        $cartProducts = CartProduct::where('user_id', $user->id)->get();
        self::assertCount(1, $cartProducts);

        $cartProduct = $cartProducts->first();
        self::assertSame($product->id, $cartProduct->product_id);
        self::assertSame(2, $cartProduct->quantity);
    }

    /** @test */
    public function it_is_stored_in_the_session_for_annonymous_users() {
        $product = factory(Product::class)->make(['id' => 1]);

        $cart = Cart::get();
        $cart->addProduct($product->id, 2);

        $cart = session('cart');
        self::assertNotNull($cart);
        self::assertSame(2, $cart->getProductQuantity(1));
    }

    /** @test */
    public function it_imports_a_cart_from_a_session() {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create();

        $cart = Cart::get();
        $cart->addProduct($product->id, 3);

        $this->be($user);
        Cart::get(); // This will import from the session.

        $cartProducts = CartProduct::where('user_id', $user->id)->get();
        self::assertCount(1, $cartProducts);

        $cartProduct = $cartProducts->first();
        self::assertSame($product->id, $cartProduct->product_id);
        self::assertSame(3, $cartProduct->quantity);
    }
}
