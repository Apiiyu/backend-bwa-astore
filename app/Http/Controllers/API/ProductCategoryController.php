<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        // ---> Get Params
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        // ---> Conditional Params ID
        if ($id) {
            $category = ProductCategory::with('products')->find($id);

            if ($category) {
                return ResponseFormatter::success($category, 'Successfully get data category!');
            } else {
                return ResponseFormatter::error(null, 'Category not found!', 404);
            }
        }

        // ---> Create NULL Query with eloquent
        $category = ProductCategory::query();

        // ---> Conditional Params Name
        if ($name) {
            $category->where('name', 'LIKE', '%' . $name . '%');
        }

        // ---> Conditional Params Show Product
        if ($show_product) {
            $category->with('products');
        }

        return ResponseFormatter::success($category->paginate($limit), 'Successfully get data category!');
    }
}
