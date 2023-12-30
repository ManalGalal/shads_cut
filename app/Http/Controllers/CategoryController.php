<?php

namespace App\Http\Controllers;

use App\Http\Requests\createCategory;
use App\Http\Requests\updateCategory;
use App\Models\Category;

class CategoryController extends Controller {
    public function create(createCategory $request) {
        $validated = $request->validated();
        $category = Category::create($validated);
        return response(["message" => __("messages.category_created"), "category" => $category], 201);
    }
    public function update(updateCategory $request, Category $category) {
        $validated = $request->validated();
        $category->update($validated);
        return response(["message" => __("messages.category_updated"), "cateogry" => $category]);
    }
    public function delete(Category $category) {
        $category->delete();
        return response(["message" => __("messages.category_deleted")]);
    }
    public function getById(Category $category) {
        return response(["category" => $category]);
    }

    public function getAll() {
        return response(["categories" => Category::orderBy('sort_order')->get()]);
    }
}
