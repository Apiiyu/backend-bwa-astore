<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        // ---> Get Params
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        // ---> Conditional Params ID
        if ($id) {
            $product = Product::with(['category', 'galleries'])->find($id);

            if ($product) {
                return ResponseFormatter::success($product, 'Successfully get data product!');
            } else {
                return ResponseFormatter::error(null, 'Product not found!', 404);
            }
        }

        // ---> Get All Data Product
        $product = Product::with(['category', 'galleries']);

        // ---> Conditional Params Name
        if ($name) {
            $product->where('name', 'LIKE', '%' . $name . '%');
        }

        // ---> Conditional Params Description
        if ($description) {
            $product->where('description', 'LIKE', '%' . $description . '%');
        }

        // ---> Conditional Params Tags
        if ($tags) {
            $product->where('tags', 'LIKE', '%' . $tags . '%');
        }

        // ---> Conditional Params Categories
        if ($categories) {
            $product->where('categories', $categories);
        }

        // ---> Conditional Params Price From
        if ($price_from) {
            $product->where('price', '>=', $price_from);
        }

        // ---> Conditional Params Price To
        if ($price_to) {
            $product->where('price', '<=', $price_to);
        }

        return ResponseFormatter::success($product->paginate($limit), 'Successfully get data product!');
    }
}
