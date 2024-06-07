<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ReportPenjualanController extends Controller
{
    public function index(Request $request)
    {
        
        $userId = Auth::user()->id;
        $data = Transaction::with(['transactionDetails'])->get();

        if ($request->ajax()) {

            $data = Transaction::with(['transactionDetails', 'user', 'transactionDetails.product'])->get();

            return DataTables::of($data)
                    ->addIndexColumn()            
                    ->addColumn('user_label', function ($row) {
                        return $row->user->name; // or any attribute you want to use as label
                    })
                    ->addColumn('document_label', function ($row) {
                        return $row->document_code .' - '. $row->document_number; // or any attribute you want to use as label
                    })
                    ->addColumn('total_label', function ($row) {
                        return 'Rp. '.number_format($row->total, 0, ',', '.'); // or any attribute you want to use as label
                    })
                    ->addColumn('date_label', function ($row) {
                        return $row->date; // or any attribute you want to use as label
                    })
                    ->addColumn('item_label', function ($row) {
                        $productNames = $row->transactionDetails->map(function ($detail) {
                            return '<div>' . $detail->product->product_name . ' x ' . $detail->quantity . '</div>';
                        })->join('');
                        return $productNames;
                    })->rawColumns(['item_label'])
                    ->make(true);
        }
        
        $variableToView = ['data', 'userId'];
        return view('report-penjualan.index')->with(compact($variableToView));
    }

    
}
