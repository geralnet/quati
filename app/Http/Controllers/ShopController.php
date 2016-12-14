<?php

namespace App\Http\Controllers;

use App\Models\Shop\Category;
use App\Models\Shop\Image;
use App\Models\Shop\Path;
use App\Models\Shop\Product;
use App\Models\Shop\ProductImage;
use Illuminate\View\View;
use Storage;
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

        if ($component instanceof Image) {
            return $this->getShopImage($component);
        }

        if ($component instanceof ProductImage) {
            return $this->getShopProductImage($component);
        }

        throw new ServiceUnavailableHttpException(get_class($component));
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

    private function getShopProductImage(ProductImage $image) {
        $path = $image->file->real_path;
        $data = Storage::drive('uploads')->get($path);
        return $data;
    }
}
