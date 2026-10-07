<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * 1. Hiển thị danh sách danh mục trong Admin (Lab 3)
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * 2. Hiển thị form thêm mới danh mục
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * 3. Xử lý lưu danh mục mới vào database
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        Category::create($validatedData);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công.');
    }

    /**
     * 4. Hiển thị chi tiết danh mục trong Admin
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * 5. Hiển thị form chỉnh sửa danh mục
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * 6. Xử lý cập nhật danh mục
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        $category->update($validatedData);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    /**
     * 7. Xóa danh mục (kiểm tra ràng buộc sản phẩm theo Lab 3)
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm liên quan không trước khi xóa
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục đang chứa sản phẩm.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công.');
    }

    // ==========================================
    // KHU VỰC DÀNH CHO NGƯỜI DÙNG THƯỜNG (User Methods - Lab 3)
    // ==========================================
    public function userIndex()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function showNormal(Category $category)
    {
        return view('categories.show', compact('category'));
    }
}
