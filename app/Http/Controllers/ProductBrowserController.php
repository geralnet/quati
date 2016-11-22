<?php

namespace App\Http\Controllers;

use App\Models\Product\Category;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductBrowserController extends Controller {
    public function index($category) {
        $current_category = $this->getCategoryForPath($category);
        $root_categories = Category::getRootCategories();
        $show_categories = is_null($current_category)
            ? $root_categories
            : $current_category->subcategories()->getResults();
        return view('home', compact('root_categories', 'current_category', 'show_categories'));
    }

    private function getCategoryForPath($path) {
        $path = trim($path, '/');
        if ($path == '') {
            return null;
        }

        $keywords = explode('/', $path);
        $current = null;
        while (!is_null($keyword = array_shift($keywords))) {
            $current = Category::getChildWithKeyword($current, $keyword);
            if (is_null($current)) {
                throw new NotFoundHttpException();
            }
        }
        return $current;
    }
}