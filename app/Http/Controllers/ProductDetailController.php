<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function showDetail(Request $request, $id)
    {
        $product = Product::find($id);

        $variableToView = ['product'];

        return view('products.product-detail')->with(compact($variableToView));
    }
}
