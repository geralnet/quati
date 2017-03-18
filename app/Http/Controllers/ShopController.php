<?php

namespace App\Http\Controllers;

use App\Models\Shop\Cart;
use App\Models\Shop\Category;
use App\Models\Shop\Image;
use App\Models\Shop\Path;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ShopController extends Controller {
    public function getCart() {
        $prices = Cart::get()->getCalculatePrices();
        $items = $prices['products'];
        $totalPrice = $prices['total'];
        return view('shop.cart', compact('totalPrice', 'items'));
    }

    /**
     * @param string $url
     * @return View
     * @throws ServiceUnavailableHttpException
     */
    public function getShop($url) {
        $fullpath = '/'.trim($url, '/');
        $path = Path::where('fullpath', $fullpath)->first();

        if (is_null($path)) {
            throw new NotFoundHttpException('Path not found: '.$path);
        }

        $component = $path->component;

        if ($component instanceof Product) {
            return $this->getShopProduct($component);
        }

        if ($component instanceof Category) {
            return $this->getShopCategory($component);
        }

        if ($component instanceof Image) {
            return $this->getShopImage($component);
        }

        throw new ServiceUnavailableHttpException(get_class($component));
    }

    public function putCart(Request $request) {
        $cart = Cart::get();
        if ($request->has('empty')) {
            $cart->removeAll();
        }
        else {
            $quantities = $request->input('quantities', []);
            foreach ($quantities as $id => $quantity) {
                $id = (int)$id;
                if (!Product::find($id)->exists()) {
                    abort(400, 'Invalid product id.');
                }

                $quantity = (int)$quantity;
                if ($quantity < 0) {
                    abort(400, 'Invalid product quantity.');
                }

                $cart->setProduct($id, $quantity);
            }
        }

        return redirect($request->has('checkout') ? '/@checkout' : '/@cart');
    }

    private function getShopCategory(Category $category) {
        return view('shop.category', ['category' => $category]);
    }

    private function getShopImage(Image $image) {
        $path = $image->file->real_path;
        $data = Storage::drive('uploads')->get($path);
        return $data;
    }

    private function getShopProduct(Product $product) {
        $root_categories = Category::getRoot()->getSubcategories();
        return view('shop.product', compact('root_categories', 'product'));
    }
}
