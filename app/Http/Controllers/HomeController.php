<?php

namespace App\Http\Controllers;

use App\Models\Product\Category;

class HomeController extends Controller {
    public function index() {
        $root_categories = Category::getRootCategories();
        return view('home', compact('root_categories'));
    }
}
