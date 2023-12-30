<?php

namespace App\Http\Controllers;

use App\Http\Requests\createProduct;
use App\Http\Requests\updateProduct;
use App\Http\Requests\uploadImage;
use App\Models\Category;
use App\Models\Product;
use App\Traits\DeleteFiles;
use Illuminate\Http\Request;

class ProductController extends Controller {
    use DeleteFiles;
    public function create(createProduct $request) {
        $validated = $request->validated();
        $product = Product::create($validated);
        return response(["message" => __("messages.product_created"), "product" => $product], 201);
    }
    public function update(updateProduct $request, Product $product) {
        $validated = $request->validated();
        $product->update($validated);
        return response(["message" => __("messages.product_updated"), "product" => $product]);
    }
    public function uploadImage(uploadImage $request, Product $product) {
        $file_exist = $request->hasFile("image");
        $path = null;
        if ($file_exist) {
            $path = $request->file("image")->store("product/image");
            echo 'xxx';
        }
        $this->deleteFile($product->image);
        $product["image"] = $path;
        $product->save();
        return response(["message" => __("message.image_uploaded")]);
    }
    public function delete(Product $product) {
        $product->delete();
        return response(["message" => __("messages.product_deleted")]);
    }
    public function getById(Product $product) {
        $product = Product::where("id", $product->id)
            ->with("category:id,name_en,name_ar")
            ->first();
        return response(["product" => $product]);
    }
    public function getAll(Request $request) {
        $products = Product::with("category:id,name_en,name_ar")
            ->paginate($request->number)
            ->withQueryString();
        return response(["products" => $products]);
    }
    public function getFeaturedProducts() {
        $featured_products = Product::where("featured", true)
            ->with("category:id,name_en,name_ar")
            ->get();
        return response(["products" => $featured_products]);
    }
    public function getProductFor(Category $category) {
        $products = $category->products()->get();
        return response(["products" => $products]);
    }
    public function getProductsForBranch(Request $request) {
        if (!$request->user()->isSuperAdmin()) {

            $products = $request->user()->branch
                ->products()
                ->with(["category:id,name_en,name_ar"])
                ->paginate()
                ->withQueryString();    
            return response(["products" => $products]);
        }
        return $this->getAll($request);
    }
}
