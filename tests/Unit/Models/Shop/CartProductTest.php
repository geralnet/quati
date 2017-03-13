<?php
declare(strict_types = 1);

namespace Tests\Unit\Models\Shop;

use App\Models\Shop\CartProduct;
use App\Models\Shop\Product;
use App\User;
use Illuminate\Database\QueryException;
use Tests\Unit\TestCase;

/**
 * Class CartProductTest
 */
class CartProductTest extends TestCase {
    /** @test */
    public function it_persists_in_the_database() {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create();

        $original = new CartProduct();
        $original->user_id = $user->id;
        $original->product_id = $product->id;
        $original->quantity = 1;
        $original->save();

        $fetched = CartProduct::find($original->id);
        self::assertNotNull($fetched);

        foreach (['id', 'user_id', 'product_id', 'quantity'] as $field) {
            self::assertEquals($original->$field, $fetched->$field, 'Field: '.$field);
        }
    }

    /** @test */
    public function it_requires_a_user() {
        $product = factory(Product::class)->create();

        $original = new CartProduct();
        $original->user_id = 0;
        $original->product_id = $product->id;
        $original->quantity = 1;

        self::expectException(QueryException::class);
        $original->save();
    }

    /** @test */
    public function it_requires_a_product() {
        $user = factory(User::class)->create();

        $original = new CartProduct();
        $original->user_id = $user->id;
        $original->product_id = 0;
        $original->quantity = 1;

        self::expectException(QueryException::class);
        $original->save();
    }

    /** @test */
    public function it_can_add_quantity() {
        $product = new CartProduct();
        $product->addQuantity(4);
        self::assertSame(4, $product->quantity);
    }

    /** @test */
    public function it_can_set_quantity() {
        $product = new CartProduct();
        $product->setQuantity(2);
        self::assertSame(2, $product->quantity);
    }
}
