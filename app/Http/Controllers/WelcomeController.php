<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Hiển thị trang chủ cửa hàng
     */
    public function index()
    {
        $dbProducts = Product::with('categoryRel')->orderBy('id', 'desc')->get();
        $specialProducts = Product::where('is_special', true)->orderBy('updated_at', 'desc')->take(3)->get();
        $dbCategories = Category::orderBy('sort_order', 'asc')->get();
        
        $categoriesById = $dbCategories->keyBy('id');
        $catCounts = ['all' => $dbProducts->count()];

        foreach ($dbCategories as $cat) {
            $catCounts[$cat->slug] = 0;
        }

        foreach ($dbProducts as $p) {
            $cat = $categoriesById->get($p->category_id);
            if ($cat && isset($catCounts[$cat->slug])) {
                $catCounts[$cat->slug]++;
            }
        }

        return view('welcome', compact('dbProducts', 'specialProducts', 'dbCategories', 'catCounts'));
    }
}
