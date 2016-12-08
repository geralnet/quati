<?php

namespace App\Http\Controllers;

use App\Models\Shop\Category;
use App\Models\Shop\Path;
use App\Models\Shop\Product;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ShopController extends Controller {
    /** @deprecated */
    public static function getShopItemForKeyword(Category $category, string $keyword) {
        if (!is_null($product = Product::where('category_id', $category->id)->where('keyword', $keyword)->first())) {
            return $product;
        }

        if (!is_null($subcategory = Category::where('parent_id', $category->id)->where('keyword', $keyword)->first())) {
            return $subcategory;
        }

        return null;
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
        $cats = $category->subcategories;
        return view('shop.category', ['category' => $category]);
    }

    private function getShopProduct(Product $product) {
        $root_categories = Category::getRoot()->subcategories();
        return view('shop.product', compact('root_categories', 'product'));
    }
}
