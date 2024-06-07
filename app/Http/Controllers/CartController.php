<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $cart = Cart::with(['product'])->get();
        $totalCart = Cart::where('user_id', $userId)->sum('quantity');

        $totalPayment = 0;

        foreach ($cart as $cartItem) {
            $productPrice = !empty($cartItem->product->discount) ? intval($cartItem->product->price) - (intval($cartItem->product->price) * intval($cartItem->product->discount)) / 100 : $cartItem->product->price;

            $itemTotal = $productPrice * $cartItem->quantity;

            $totalPayment += $itemTotal;
        }

        $variableToView = ['cart', 'totalCart', 'totalPayment'];

        return view('cart.cart-list')->with(compact($variableToView));
    }

    public function countCart()
    {
        try {
            $userId = Auth::user()->id;
            $count = Cart::where('user_id', $userId)->sum('quantity');
            return response()->json([
                'data' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function removeCart($id)
    {
        Cart::where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Cart Berhasil Dihapus!.',
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Ambil keranjang belanja yang akan diperbarui
        $cart = Cart::find($id);

        // Perbarui jumlah barang dalam keranjang belanja
        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Data Cart Berhasil Diupdate!.',
        ]);
    }

    public function clearCart(Request $request)
{
    try {
        // Menghapus semua item di keranjang belanja untuk user yang sesuai
        Cart::where('user_id', $request->user_id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Data Cart Berhasil Diupdate!.',
        ],200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Gagal menghapus keranjang'], 500);
    }
}
}
