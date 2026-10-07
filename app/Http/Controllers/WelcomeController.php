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
        $dbProducts = Product::with('category')->orderBy('id', 'desc')->get();
        $specialProducts = Product::where('is_special', true)->orderBy('updated_at', 'desc')->take(3)->get();
        $dbCategories = Category::orderBy('sort_order', 'asc')->get();
        
        $catCounts = ['all' => $dbProducts->count()];

        foreach ($dbCategories as $cat) {
            $catCounts[$cat->slug] = 0;
        }

        foreach ($dbProducts as $p) {
            if (is_object($p->category) && isset($p->category->slug)) {
                $slug = $p->category->slug;
                if (isset($catCounts[$slug])) {
                    $catCounts[$slug]++;
                }
            }
        }

        return view('welcome', compact('dbProducts', 'specialProducts', 'dbCategories', 'catCounts'));
    }
}
