<?php

namespace App\Http\Controllers;

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use App\Models\Shop\Product;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ShopController extends Controller {
    /**
     * @param string $url
     * @return View
     * @throws ServiceUnavailableHttpException
     */
    public function getShop($url) {
        $fullpath = '/'.trim($url, '/');
        $path = Path::where('fullpath', $fullpath)->first();

        if (is_null($path)) {
            throw new NotFoundHttpException();
        }

        $component = $path->component;

        if ($component instanceof Product) {
            return $this->getShopProduct($component);
        }

        if ($component instanceof Category) {
            return $this->getShopCategory($component);
        }

        throw new ServiceUnavailableHttpException();
    }

    private function getShopCategory(Category $category) {
        return view('shop.category', ['category' => $category]);
    }

    private function getShopProduct(Product $product) {
        $root_categories = Category::getRoot()->getSubcategories();
        return view('shop.product', compact('root_categories', 'product'));
    }
}
