<?php

namespace App\Repositories\Master;

use App\Helpers\CustomHelper;
use App\Models\Master\ProductUpload;
use App\Models\Order\Transaction;
use App\Models\Order\TransactionDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TransactionRepository
{
    protected static function getPaginationSize($perPage)
    {
        $perPageAllowed = [20, 50, 100, 200];

        if (in_array($perPage, $perPageAllowed)) {
            return $perPage;
        }

        return $perPage < 10 ? $perPage : 10;
    }

    protected static function searchRow($request, $records)
    {
        // search
        if ($request->has('searchKey') && !empty($request->searchValue)) {
            if (in_array($request->searchKey, ['id', 'transaksi_date'])) {
                $records = $records->where($request->searchKey, 'LIKE', '%' . $request->searchValue . '%');
            }
        }

        if ($request->searchKey == 'document_label') {
            $records = $records->whereRaw("CONCAT(doc_code, '-', doc_number) LIKE ?", ['%' . $request->searchValue . '%']);
        }

        if ($request->searchKey == 'user_label') {
            $records = $records->whereHas('users', function (Builder $query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->searchValue . '%');
            });
        }

        // filter
        if ($request->has('type')) {
            $records = $records->where('type', 'LIKE', '%' . $request->type . '%');
        }

        return $records;
    }

    protected static function sortRow($sortBy, $orderBy, $records)
    {
        // if ($sortBy == 'pack_type_label') {
        //     $records->orderBy('status', 'asc');
        // }

        $records = $records->orderBy($sortBy, $orderBy);

        return $records;
    }

    public static function getTransactionsAll($request, $sortBy, $orderBy, $perPage, $limit = null)
    {
        $data = Transaction::query();

        $perPage = self::getPaginationSize($perPage);
        $data = self::searchRow($request, $data);
        $data = self::sortRow($sortBy, $orderBy, $data);

        if (!empty($limit)) {
            $data = $data->take($limit)
                ->get();
        }

        $data = $data->paginate($perPage)->withQueryString();

        return ['results' => $data->items(), 'link' => $data->hasPages() ? $data->linkCollection() : []];
    }

    // index
    public static function listTransactions(Request $request, $page, $limit = null)
    {
        try {

            $sortBy = $request->sortBy ?? 'created_at';
            $orderBy = $request->orderBy ?? 'desc';
            $perPage = $request->perPage ?? 10;

            $results = self::getTransactionsAll($request, $sortBy, $orderBy, $perPage, $limit);

            return [
                'status' => 200,
                'data' => $results,
            ];
        } catch (\Throwable $th) {
            Log::critical('TransactionRepository - listTransactions', ['message' => $th->getMessage(), 'line' => $th->getLine()]);

            return ['status' => 500, 'message' => 'Error (code 0)'];
        }
    }

    public static function listArrayLookup()
    {

        try {
            $arrayLookUp = [];

            // $arrayLookUp['source_id'] = Party::select('id as key', 'name as value')->pluck('value', 'key');
            // $arrayLookUp['destination_id'] = Party::select('id as key', 'name as value')->pluck('value', 'key');

            return [
                'status' => 200,
                'data' => [
                    'arrayLookUp' => $arrayLookUp,
                    'status' => true,
                ],
            ];
        } catch (\Throwable $th) {
            return ['status' => 500, 'message' => 'Error (code 0)'];
        }
    }

    public static function store($request)
    {
        try {
            $transaction = DB::transaction(function () use ($request) {

                $data = new Transaction();

                self::commonUpdates($data, $request);
                $data->save();

                // detail movement
                self::commonUpdatesDetail1($data, $request['inboundformdetailmovement'] ?? null);

                return compact('data');
            });

            if (is_array($transaction)) {
                extract($transaction);

                return [
                    'status' => 200,
                    'data' => [
                        'data' => Transaction::with(['details'])->find($data->id),
                        'message' => 'Updated Successfully',
                        'success' => true,
                    ],
                ];
            } else {
                return $transaction;
            }
        } catch (\Throwable $th) {
            Log::critical('InboundRepository - createInbound', ['message' => $th->getMessage(), 'line' => $th->getLine()]);

            return [
                'status' => 500,
                'message' => $th->getMessage(),
            ];
        }
    }

    public static function edit($id)
    {
        try {

            $data = Transaction::with(['details'])->where('id', $id)->get()->first();

            if (empty($data)) {
                return [
                    'status' => 404,
                    'data' => [
                        'data' => [],
                        'message' => 'Data Not Found',
                        'success' => false,
                    ],
                ];
            }

            $arrayLookUp = [];

            return [
                'status' => 200,
                'data' => [
                    'data' => $data,
                    'arrayLookUp' => $arrayLookUp,
                    'status' => true,
                ],
            ];
        } catch (\Throwable $th) {
            Log::critical('TransactionRepository - edit', ['message' => $th->getMessage(), 'line' => $th->getLine()]);

            return ['status' => 500, 'message' => 'Error (code 0)'];
        }
    }

    public static function update(Request $request, $id)
    {
        $userId = Auth::user()->id;
        // dd($request->all());
        try {

            $transaction = DB::transaction(function () use ($request, $userId, $id) {

                $data = Transaction::find($id);
                self::commonUpdates($data, $request);

                $data->save();

                // Delete Detail Upload
                $fileDeleteId = CustomHelper::niceExplode('|', $request->file_delete_upload);
                if (!empty($fileDeleteId)) {
                    $tmpRows = DB::table('product_uploads')
                        ->whereIn('id', $fileDeleteId)
                        ->where('product_id', $id)
                        ->select('file_path')
                        ->get();

                    foreach ($tmpRows as $row) {
                        if (File::exists(public_path($row->file_path))) {
                            File::delete(public_path($row->file_path));
                        }
                    }

                    DB::table('product_uploads')
                        ->whereIn('id', $fileDeleteId)
                        ->where('product_id', $id)
                        ->delete();
                }

                // detail upload
                if (!empty($request->upload)) {
                    $productUpload = new ProductUpload();
                    self::commonUpdatesUpload($data, $productUpload, $request);

                    $productUpload->save();
                }

                return compact('data');
            }); // transaction

            if (is_array($transaction)) {
                extract($transaction);

                return [
                    'status' => 200,
                    'data' => [
                        'data' => Transaction::with(['uploads'])->find($data->id),
                        'message' => 'Created Successfully.',
                        'success' => true,
                    ],
                ];
            } else {
                return $transaction;
            }
        } catch (\Throwable $th) {
            return [
                'status' => 500,
                'data' => [],
                'message' => $th->getMessage(),
                'success' => false,
            ];
        }
    }

    public static function destroy($id)
    {
        $data = Transaction::find($id);

        if (empty($data)) {
            return [
                'status' => 404,
                'data' => [
                    'data' => [],
                    'message' => 'Data Not Found',
                    'success' => false,
                ],
            ];
        }

        $productUpload = $data->uploads;
        DB::transaction(function () use ($data, $productUpload) {
            if (!empty($productUpload)) {
                if (File::exists(public_path($productUpload?->file_path))) {
                    File::delete(public_path($productUpload?->file_path));
                }

                $productUpload->delete();
            }

            $data->delete();
        });

        return [
            'status' => 200,
            'data' => [
                'data' => [],
                'message' => 'Deleted Data Successfully',
                'success' => true,
            ],
        ];
    }

    private static function commonUpdates($data, Request $request)
    {
        $userId = Auth::user()->id;
        $data->user_id = $request->user_id;
        $data->doc_code = $request->doc_code;
        $data->doc_number = $request->doc_number;

        $total = CustomHelper::reverseMoneyMask($request->total);
        $data->total = $total;

        $data->updated_by_id = $userId;
    }

    private static function commonUpdatesDetail1($data, $detailMovement)
    {
        $updatedId = [];
        $oldDataDetail = TransactionDetail::where('transaction_id', $data->id)->get();
        if (!empty($detailMovement)) {
            $dataDetail = null;
            foreach ($detailMovement as $key => $item) {
                if (array_key_exists('id', $item)) {
                    $dataDetail = $oldDataDetail->where('id', $item['id'])->first();
                    if (!empty($dataDetail)) {
                        $updatedId[] = $item['id'];
                    }
                } else {
                    $dataDetail = new TransactionDetail();
                    $dataDetail->transaction_id = $data->id;
                }
                if (!empty($dataDetail)) {
                    $dataDetail->product_id = $item['product_id'];
                    $dataDetail->quantity = $item['quantity'];
                    $dataDetail->sub_total = $item['sub_total'];
                    $dataDetail->save();
                }
            }
        }

        $toBeDeleted = $oldDataDetail->whereNotIn('id', $updatedId);
        foreach ($toBeDeleted as $row) {
            $row->delete();
        }
    }

    private static function commonUpdatesUpload($data, $productUpload, Request $request)
    {
        $userId = Auth::user()->id;

        $randomName = Str::orderedUuid();
        $originalFileName = pathinfo($request->upload->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = CustomHelper::uploadFile('assets/product_thumbnail', $originalFileName, $request->upload, $randomName);
        $productUpload->product_id = $data->id;
        $productUpload->file_path = 'assets/product_thumbnail/' . $fileName;

        $productUpload->original_file_name = $originalFileName;

        $data->updated_by_id = $userId;
    }
}
