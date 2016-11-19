<?php

namespace App\Http\Controllers;

use App\Models\Product\Category;

class HomeController extends Controller {
    public function index() {
        $categories = Category::getRootCategories();
        return view('home', compact('categories'));
    }
}
