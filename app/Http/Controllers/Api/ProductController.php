<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function category()
    {
        $products = Category::all();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $products
        ]);
    }
    public function product(Request $request)
    {
        $products = Product::all();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => ProductResource::collection($products)
        ]);
    }

    public function productByCategory(Request $request, $category_id)
    {
        $products = Product::where('category_id', $category_id)->get();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => ProductResource::collection($products)
        ]);
    }
}
