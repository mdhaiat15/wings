<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(6);

        $variableToView = ['products'];

        return view('products.product-list')->with(compact($variableToView));
    }

    public function showDetail($id)
    {
        $product = Product::find($id);

        $variableToView = ['product'];

        return view('products.product-detail')->with(compact($variableToView));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $existingCart = Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingCart) {
            $existingCart->quantity += 1;
            $existingCart->save();
        } else {
            $cart = new Cart();
            $cart->user_id = Auth::user()->id;
            $cart->product_id = $productId;
            $cart->quantity = $quantity;
            $cart->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'data' => $cart ?? null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
