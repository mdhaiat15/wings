<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document_code' => 'required|string|max:3',
        ]);

        $lastDocumentNumber = empty(Transaction::count()) ? 1 : Transaction::count() + 1;

        $newDocumentNumber = str_pad($lastDocumentNumber, 3, '0', STR_PAD_LEFT);

        $cartData = $request->cart;

        foreach ($cartData as $cart) {
            $hargaKeseluruhan = !empty($cart['product']['discount']) ? intval($cart['product']['price']) - (intval($cart['product']['price']) * intval($cart['product']['discount'])) / 100 : $cart['product']['price'];
            $sub_total = $hargaKeseluruhan * $cart['quantity'];
        }

        try {
            DB::beginTransaction();

            $transaction = new Transaction();
            $transaction->document_code = 'TRX';
            $transaction->document_number = $newDocumentNumber;
            $transaction->user_id = Auth::user()->id;
            $transaction->total = $request->total;
            $transaction->date = Carbon::now();
            $transaction->save();

            foreach ($cartData as $cart) {
                $hargaKeseluruhan = !empty($cart['product']['discount']) ? intval($cart['product']['price']) - (intval($cart['product']['price']) * intval($cart['product']['discount'])) / 100 : $cart['product']['price'];
                $sub_total = $hargaKeseluruhan * $cart['quantity'];
                
                $transactionDetail = new TransactionDetail();
                $transactionDetail->transaction_id = $transaction->id;
                $transactionDetail->product_id = $cart['product_id'];
                $transactionDetail->price = $cart['product']['price'];
                $transactionDetail->quantity = $cart['quantity'];
                $transactionDetail->unit = $cart['product']['unit'];
                $transactionDetail->sub_total = $sub_total;
                $transactionDetail->currency = $cart['product']['currency'];
                $transactionDetail->save();
            }

            DB::commit();

            return response()->json(['message' => 'Transaksi berhasil disimpan'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
