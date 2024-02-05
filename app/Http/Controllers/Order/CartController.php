<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Cart;
use App\Models\Order\Transaction;
use App\Models\Order\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private static function getBreadCrumbList()
    {
        $breadCrumbList = [
            [
                'text' => 'Cart List',
                'link' => route('cart.index'),
            ],
        ];

        return $breadCrumbList;
    }

    private static function getSidebars()
    {
        $sidebars = [];

        return $sidebars;
    }


    public static $actionFlow =
    [];

    private static $formName = 'cart';
    private static $urlParam = 'cart';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Cart::with(['product', 'product.uploads'])->get();

        $breadCrumbList = self::getBreadCrumbList();
        $title = $breadCrumbList[0]['text'];

        $sidebars = self::getSidebars();

        $formName = self::$formName;

        $action = [
            // 'text' => '',
            // 'link' => route(''),
        ];

        $secondaryTitle = 'Cart';

        $secondaryAction = [
            // 'text' => 'Create',
            // 'link' => route('product.create'),
        ];

        $routeName = 'cart.index';
        $overrideRouteUrlParam = self::$urlParam;
        $overrideKeyId = 'id';

        $arrayLookup = [];

        $hideTitle = true;

        $subTotalCart = null;
        $totalCart = null;
        $cartProduct = collect();
        foreach ($data as $value) {
            $cartProduct->push(
                $value->product->id,
            );

            if (!empty($value->product?->discount)) {

                $productDiscount = $value->product?->price - ($value->product?->price * $value->product?->discount / 100);
                $subTotalCart += $productDiscount * $value->quantity;
            } else {
                $subTotalCart += $value->product?->price * $value->quantity;
                $totalCart += $subTotalCart;
            }
        }

        $cartProduct = json_encode($cartProduct);

        $variableToView = ['breadCrumbList', 'title', 'secondaryTitle', 'action', 'secondaryAction', 'sidebars', 'data', 'routeName', 'overrideRouteUrlParam', 'overrideKeyId', 'hideTitle', 'formName', 'subTotalCart', 'cartProduct'];
        return view('page.order.cart')->with(compact($variableToView));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::user()->id;

        DB::transaction(function () use ($request, $userId) {

            $data = new Cart();
            $data = $request->input('product_id');
            $data->created_by_id = $userId;
            $data->save();
        });

        return redirect()->route('product.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::user()->id;

        DB::transaction(function () use ($request, $userId, $id) {

            $data = Cart::find($id);
            $data->quantity = $request->quantity;
            $data->price = $request->price;
            $data->sub_total = $request->sub_total;
            $data->save();
        });

        return redirect()->route('cart.index')->with('status-success', 'Updated Cart successfully!');
    }

    public function destroy($id)
    {
        $data = Cart::find($id);

        DB::transaction(function () use ($data) {
            $data->delete();
        });

        return redirect()->route('cart.index')->with('status-success', 'Delete Product from cart successfully!');
    }

    public function checkOut(Request $request)
    {
        $userId = Auth::user()->id;
        $now = Carbon::now();

        $cartProduct = json_decode($request->cartProduct);

        $dataCart = Cart::with(['product', 'product.uploads'])->whereIn('product_id', $cartProduct)->where('user_id', $userId)->get();

        $subTotalCart = null;
        $totalCart = null;
        foreach ($dataCart as $value) {

            if (!empty($value->product?->discount)) {

                $productDiscount = $value->product?->price - ($value->product?->price * $value->product?->discount / 100);
                $subTotalCart += $productDiscount * $value->quantity;
            } else {
                $subTotalCart += $value->product?->price * $value->quantity;
                $totalCart += $subTotalCart;
            }
        }
        $totalCart = $subTotalCart;

        if (!empty($dataCart) && $dataCart->count() > 0) {
            DB::transaction(function () use ($userId, $now, $dataCart, $totalCart) {
                $transaction = new Transaction();
                $transaction->user_id = $userId;
                $transaction->doc_code = 'TRX';
                $transaction->doc_number = 0;
                $transaction->transaksi_date = $now;
                $transaction->total = $totalCart;
                $transaction->created_by_id = $userId;
                $transaction->save();

                $id = $transaction->id;
                $transaction = Transaction::find($id);
                $transaction->doc_number = str_pad($transaction->id, 3, '0', STR_PAD_LEFT);
                $transaction->save();

                $productDiscount = null;
                $subTotal = null;
                foreach ($dataCart as $cart) {
                    if (!empty($cart->product?->discount) && $cart->product?->discount !== 0) {
                        $productDiscount = $cart->product?->price - ($cart->product?->price * $cart->product?->discount / 100);

                        $subTotal = $productDiscount * $cart->quantity;
                    } else {
                        $subTotal = $cart->product?->price * $cart->quantity;
                    }

                    $transDetail = new TransactionDetail();
                    $transDetail->transaction_id = $transaction->id;
                    $transDetail->product_id = $cart->product_id;
                    $transDetail->quantity = $cart->quantity;
                    $transDetail->sub_total = $subTotal;
                    $transDetail->save();
                }

                DB::table('carts')->delete();
            });

            $returnMsg = __('Check Out Berhasil!');
        } else {
            $returnMsg = __('Gagal Check Out');
        }

        return redirect()->route('product-list.index')->with('status-success', $returnMsg);
    }
}
