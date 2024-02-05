<?php

namespace App\Repositories\Master;

use App\Helpers\CustomHelper;
use App\Models\Master\Party;
use App\Models\Master\Product;
use App\Models\Master\ProductUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductRepository
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
            if (in_array($request->searchKey, ['id', 'doc_date', 'name', 'code'])) {
                $records = $records->where($request->searchKey, 'LIKE', '%' . $request->searchValue . '%');
            }
        }

        // filter
        if ($request->has('type')) {
            $records = $records->where('type', 'LIKE', '%' . $request->type . '%');
        }

        return $records;
    }

    protected static function sortRow($sortBy, $orderBy, $records)
    {
        if ($sortBy == 'pack_type_label') {
            $records->orderBy('status', 'asc');
        }

        $records = $records->orderBy($sortBy, $orderBy);

        return $records;
    }

    public static function getProductsAll($request, $sortBy, $orderBy, $perPage, $limit = null)
    {
        $data = Product::query();

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
    public static function listProducts(Request $request, $page, $limit = null)
    {
        try {

            $sortBy = $request->sortBy ?? 'created_at';
            $orderBy = $request->orderBy ?? 'desc';
            $perPage = $request->perPage ?? 10;

            $results = self::getProductsAll($request, $sortBy, $orderBy, $perPage, $limit);

            return [
                'status' => 200,
                'data' => $results,
            ];
        } catch (\Throwable $th) {
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
        $userId = Auth::user()->id;

        try {

            $transaction = DB::transaction(function () use ($request, $userId) {

                $data = new Product();
                self::commonUpdates($data, $request);
                $data->created_by_id = $userId;
                $data->save();

                $productUpload = new ProductUpload();
                self::commonUpdatesUpload($data, $productUpload, $request);

                $productUpload->save();

                return compact('data');
            }); // transaction


            if (is_array($transaction)) {
                extract($transaction);

                return [
                    'status' => 200,
                    'data' => [
                        'data' => Product::with(['uploads'])->find($data->id),
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

    public static function edit($id)
    {
        $data = Product::find($id);

        if (empty($data)) {
            return
                [
                    'status' => 404,
                    'data' => [
                        'data' => [],
                        'message' => 'Data Not Found',
                        'success' => false,
                    ],
                ];
        }

        $arrayLookUp = [];

        return
            [
                'status' => 200,
                'data' => [
                    'data' => $data,
                    'arrayLookUp' => $arrayLookUp,
                    'status' => true,
                ],
            ];
    }

    public static function update(Request $request, $id)
    {
        $userId = Auth::user()->id;
        // dd($request->all());
        try {

            $transaction = DB::transaction(function () use ($request, $userId, $id) {

                $data = Product::find($id);
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
                        'data' => Product::with(['uploads'])->find($data->id),
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
        $data = Product::find($id);

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

    public static function commonUpdates($data, Request $request)
    {
        $userId = Auth::user()->id;
        $data->name = $request->name;
        $data->code = $request->code;
        $data->price = $request->price;
        $data->currency = $request->currency;

        if (empty($request->discount)) {
            $data->discount = 0;
        } else {
            $data->discount = $request->discount;
        }

        $data->dimension = $request->dimension;
        $data->unit = $request->unit;

        $data->updated_by_id = $userId;
    }

    public static function commonUpdatesUpload($data, $productUpload, Request $request)
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
