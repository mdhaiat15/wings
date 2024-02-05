<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Master\Product;
use App\Models\Order\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductListController extends Controller
{
    private static function getBreadCrumbList()
    {
        $breadCrumbList = [
            [
                'text' => 'Product List',
                'link' => route('product-list.index'),
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

    private static $formName = 'product';
    private static $urlParam = 'product';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with(['uploads'])->get();

        $breadCrumbList = self::getBreadCrumbList();
        $title = $breadCrumbList[0]['text'];

        $sidebars = self::getSidebars();

        $formName = self::$formName;

        $action = [
            // 'text' => '',
            // 'link' => route(''),
        ];

        $secondaryTitle = 'All Product';

        $secondaryAction = [
            // 'text' => 'Create',
            // 'link' => route('product.create'),
        ];

        $routeName = 'product.index';
        $overrideRouteUrlParam = self::$urlParam;
        $overrideKeyId = 'id';

        $arrayLookup = [];

        $hideTitle = true;

        $variableToView = ['breadCrumbList', 'title', 'secondaryTitle', 'action', 'secondaryAction', 'sidebars', 'data', 'routeName', 'overrideRouteUrlParam', 'overrideKeyId', 'hideTitle', 'formName'];
        return view('page.order.product-list')->with(compact($variableToView));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $userId = Auth::user()->id;

        $checkCart = null;

        $checkCart = Cart::where('product_id', $request->product_id)->first();

        DB::transaction(function () use ($request, $userId, $checkCart) {

            if (!empty($checkCart)) {
                $cart = $checkCart;
                $this->commonUpdated($userId, $request, $cart, $checkCart);
                $cart->updated_by_id = $userId;
                $cart->save();
            } else {
                $cart = new Cart();
                $this->commonUpdated($userId, $request, $cart, $checkCart);
                $cart->created_by_id = $userId;
                $cart->save();
            }
        });

        return redirect()->route('product-list.index')->with('status-success', 'Product added to cart successfully!');
    }

    function commonUpdated($userId, Request $request, $cart, $checkCart)
    {
        $cart->user_id = $userId;
        $cart->product_id = $request->product_id;
        $cart->quantity = (!empty($checkCart->quantity) ? $checkCart->quantity : 0) + 1;

        $cart->price = $request->price;
        $cart->sub_total = (!empty($checkCart->quantity) ? $checkCart->quantity : 0) * (!empty($request->product_discount) ? $request->product_discount : $request->price);
    }
}
