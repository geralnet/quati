<?php

namespace App\Http\Controllers;

use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ShopController extends Controller {
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
     * @param $path
     * @return View
     * @throws ServiceUnavailableHttpException
     */
    public function getShop($path) {
        $item = $this->getShopItemForPath($path);

        if (is_null($item)) {
            throw new NotFoundHttpException();
        }

        if ($item instanceof Product) {
            return $this->getShopProduct($item);
        }

        if ($item instanceof Category) {
            return $this->getShopCategory($item);
        }

        throw new ServiceUnavailableHttpException();
    }

    private function getShopCategory(Category $category) {
        return view('shop.category', ['category' => $category]);
    }

    private function getShopItemForPath(string $path) {
        $path = trim($path, '/');
        $current = Category::getRoot();
        if ($path == '') {
            return $current;
        }

        $keywords = explode('/', $path);
        while (!is_null($keyword = array_shift($keywords))) {
            $current = self::getShopItemForKeyword($current, $keyword);
            if (is_null($current)) {
                return null;
            }
        }
        return $current;
    }

    private function getShopProduct(Product $product) {
        $root_categories = Category::getRoot()->subcategories();
        return view('shop.product', compact('root_categories', 'product'));
    }
}
