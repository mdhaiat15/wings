<?php

namespace App\Http\Controllers\Master;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Order\Cart;
use App\Models\Order\Transaction;
use App\Repositories\Master\TransactionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    private static function getBreadCrumbList()
    {
        $breadCrumbList = [
            [
                'text' => 'Transaction List',
                'link' => route('transaction.index'),
            ],
        ];

        return $breadCrumbList;
    }

    private static function getSidebars()
    {
        $sidebars = [];

        return $sidebars;
    }

    private static function getMetaFieldForList()
    {
        $tableKeyList = [
            'id' => [
                'title' => __('ID'),
                'show-search' => 1,
                'show-sort' => 1,
            ],
            'transaksi_date' => [
                'title' => __('Transaction Date'),
                'show-search' => 1,
                'show-sort' => 1,
                'edit-link' => 'true',
                'default-search' => 'false',
            ],
            'document_label' => [
                'title' => __('Transaction'),
                'show-search' => 1,
                'edit-link' => 'true',
                'default-search' => 'true',
            ],
            'user_label' => [
                'title' => __('User'),
                'show-search' => 1,
                'show-sort' => 1,
                'edit-link' => 'true',
                'default-search' => 'true',
            ],
            'total_label' => [
                'title' => __('Total'),
                'show-search' => 0,
                'edit-link' => 'false',
                'default-search' => 'false',
            ],
        ];

        return $tableKeyList;
    }


    public static function getMetaFieldSection()
    {
        $tableKey = [
            'transaksi_date' => [
                'title' => __('Transaction Date'),
                'type' => 'date',
                'required' => 'true',
                'dbfield' => 'transaksi_date',
                'description' => '',
            ],
            'user_label' => [
                'title' => __('User'),
                'type' => 'label',
                'required' => 'true',
                'dbfield' => 'user_label',
                'description' => '',
            ],
            'doc_code' => [
                'title' => __('Document Code'),
                'type' => 'text',
                'required' => '',
                'dbfield' => 'doc_code',
                'required-placeholder' => 'true',
            ],
            'doc_number' => [
                'title' => __('Document Number'),
                'type' => 'text',
                'required' => 'true',
                'dbfield' => 'doc_number',
                'required-placeholder' => 'true',
            ],
            'total' => [
                'title' => __('Total'),
                'type' => 'text',
                'required' => 'true',
                'dbfield' => 'total',
            ],
            'form-header-transaction' => [
                'title' => 'Detail',
                'header' => 'true',
            ],
            'form-detail-transaction' => [
                'key' => 'details',
                'detail' => 'true',
                'title' => 'Detail Transaction',
                'description' => '',
                'required' => 'false',
                'details' => [
                    'transaction_id' => [
                        'title' => __('ID Transaction'),
                        'type' => 'label',
                        'required' => 'true',
                        'dbfield' => 'transaction_id',
                        'required-placeholder' => 'true',
                    ],
                    'product_label' => [
                        'title' => __('Products'),
                        'type' => 'text',
                        'required' => 'true',
                        'dbfield' => 'product_label',
                    ],
                    'quantity' => [
                        'title' => __('Quantity'),
                        'type' => 'text',
                        'required' => 'true',
                        'dbfield' => 'quantity',
                        'required-placeholder' => 'true',
                    ],
                    'sub_total' => [
                        'title' => __('Sub Total'),
                        'type' => 'text',
                        'required' => 'true',
                        'dbfield' => 'sub_total',
                        'x-mask-dynamic' => 'money',
                    ],
                ],
            ],
        ];

        return $tableKey;
    }

    public static $actionFlow =
    [];

    private static $formName = 'transaction';
    private static $urlParam = 'transaction';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page');

        $userId = auth()->user()->id;

        // $data = TransactionRepository::listArrayLookup();
        // $dataArrayLookup = $data['data']['arrayLookUp'] ?? null;

        $data = TransactionRepository::listTransactions($request, $page);
        $data = $data['data'] ?? null;

        $dataFilter['perPage'] = $request->perPage;
        $dataFilter['searchKey'] = $request->searchKey;
        $dataFilter['searchValue'] = $request->searchValue;

        $dataFilter['sortBy'] = $request->sortBy ?? 'created_at';
        $dataFilter['orderBy'] = $request->orderBy ?? 'desc';

        $breadCrumbList = self::getBreadCrumbList();
        $title = $breadCrumbList[0]['text'];

        $sidebars = self::getSidebars();
        $tableKeyList = self::getMetaFieldForList();

        $formName = self::$formName;

        $action = [
            // 'text' => '',
            // 'link' => route(''),
        ];

        $secondaryTitle = 'All Transaction';

        $secondaryAction = [
            // 'text' => 'Print',
            // 'link' => route('transaction.create'),
        ];

        $routeName = 'transaction.index';
        $routeEdit = 'transaction.edit';
        $overrideRouteUrlParam = self::$urlParam;
        $overrideKeyId = 'id';

        $arrayLookup = [];

        $hideTitle = true;
        $filterRequest = CustomHelper::getRawQueryString($request);

        $searchDateRange = 1;
        $datesearchvalue1 = $request->datesearchvalue1;
        $datesearchvalue2 = $request->datesearchvalue2;

        if ((!empty($datesearchvalue1)) && (!empty($datesearchvalue2))) {
            try {
                $tmpDate1 = Carbon::createFromFormat('Y-m-d', $datesearchvalue1);
                $tmpDate2 = Carbon::createFromFormat('Y-m-d', $datesearchvalue2);
                // $data = $data->whereBetween('doc_date', [$tmpDate1->format('Y-m-d'), $tmpDate2->format('Y-m-d')]);
            } catch (Exception $er) {
                dd('error');
                // $customError = __('Invalid date');
            }
        }

        $variableToView = ['breadCrumbList', 'title', 'secondaryTitle', 'action', 'secondaryAction', 'sidebars', 'tableKeyList', 'data', 'dataFilter', 'routeName', 'routeEdit', 'overrideRouteUrlParam', 'overrideKeyId', 'arrayLookup', 'hideTitle', 'filterRequest', 'formName'];
        return view('page.master.transaction.index')->with(compact($variableToView));
    }

    public function create(Request $request)
    {
        $filterRequest = CustomHelper::getRawQueryString($request);

        $breadCrumbList = self::getBreadCrumbList();
        $breadCrumbList[] = [
            'text' => 'Create',
            'link' => 'transaction.create',
        ];

        $secondaryTitle = 'Master Transaction Entry';

        $secondaryAction = [
            [
                'action' => 'save',
                'text' => 'Save',
                'link' => route('transaction.store'),
            ],
            [
                'action' => 'link',
                'text' => 'Back',
                'link' => route('transaction.index', $filterRequest),
            ],
        ];

        $tableKey = self::getMetaFieldSection();
        $formName = self::$formName;

        $arrayLookup = [];

        $variableToView = ['breadCrumbList', 'secondaryTitle', 'secondaryAction', 'tableKey', 'formName', 'arrayLookup'];
        return view('page.master.transaction.editor')->with(compact($variableToView));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            // 'name' => 'required',
            // 'code' => 'required',
            // 'price' => 'required',
            // 'currency' => 'required',
            // 'upload' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        $data = TransactionRepository::store($request);

        if ($data['status'] != 200) {
            return redirect()->route('transaction.create')->with(['status-error' => __('Error! Try again later')]);
        }

        $data = $data['data']['data'];

        if ($request->save_and_new === 'yes') {
            $routeNew = 'transaction.create';
            return redirect()->route($routeNew)->with(['status-success' => __('Data saved!')]);
        } else {
            return redirect()->route('transaction.edit', [self::$urlParam => $data['id']])->with(['status-success' => __('Data saved!')]);
        }
    }

    public function edit(Request $request, string $id)
    {
        $filterRequest = CustomHelper::getRawQueryString($request);

        $data = TransactionRepository::edit($id);

        $dataArrayLookup = $data['data']['arrayLookUp'] ?? null;
        $data = $data['data']['data'] ?? null;

        if (empty($data)) {
            return redirect()->route('transaction.index')->with(['status-error' => __('No Data!')]);
        }

        $actionArray = [];
        foreach (self::$actionFlow as $key => $value) {
            if ($value['status-before'] === null) {
                $statusPassed = true;
            } else {
                $statusPassed = in_array($data['status'], $value['status-before'], true);
            }
            if ($value['status-before'] === null || $statusPassed) {
                $haveAccess = true;
                if ($haveAccess) {
                    $actionArray[$key] = [
                        'text' => __($value['button-label']),
                        'link' => !empty($value['dialog-uri']) ? route($value['dialog-uri'], [self::$urlParam => $id]) : route($value['process-uri'], [self::$urlParam => $id]),
                        'method' => 'POST',
                        'icon' => '',
                        'is-popup' => (bool) empty($value['dialog-uri']),
                    ];
                }
            }
        }

        $breadCrumbList = self::getBreadCrumbList();
        $breadCrumbList[] = [
            'text' => 'Edit',
            'link' => 'transaction.edit',
        ];

        $secondaryTitle = "Transaction ($id)";

        $secondaryAction = [
            // [
            //     'action' => 'save',
            //     'text' => 'Save',
            //     'link' => route('transaction.update', [self::$urlParam => $id]),
            // ],
            // [
            //     'action' => 'delete',
            //     'text' => 'Delete',
            //     'link' => route('transaction.destroy', [self::$urlParam => $id]),
            //     'confirm' => 'Apakah yakin',
            // ],
            // [
            //     'action' => 'action',
            //     'text' => 'Action',
            //     'lists' => $actionArray,
            // ],
            [
                'action' => 'link',
                'text' => 'Back',
                'link' => route('transaction.index', $filterRequest),
            ],
        ];

        $tableKey = self::getMetaFieldSection();
        $formName = self::$formName;

        // form-detail
        $data['form-detail-transaction'] = $data[$tableKey['form-detail-transaction']['key']];

        $arrayLookup = [];

        $arrayAttachment = [];

        $alpineActive = true;
        $alpineMask = true;

        $variableToView = ['alpineMask', 'alpineMask', 'breadCrumbList', 'secondaryTitle', 'secondaryAction', 'tableKey', 'formName', 'arrayLookup', 'data', 'arrayAttachment'];
        return view('page.master.transaction.editor')->with(compact($variableToView));
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            // 'name' => 'required',
            // 'code' => 'required',
            // 'price' => 'required',
            // 'currency' => 'required',
        ]);

        $data = TransactionRepository::update($request, $id);

        if ($data['status'] == 200) {
            return redirect()->route('transaction.edit', [self::$urlParam => $id])->with(['status-success' => __('Data saved!')]);
        } else {
            return redirect()->route('transaction.edit', [self::$urlParam => $id])->with(['status-error' => __('Failed save data!')]);
        }
    }

    public function destroy(string $id)
    {
        $data = TransactionRepository::destroy($id);

        if ($data['status'] == 200) {
            return redirect()->route('transaction.index')->with(['status-success' => __('Data deleted!')]);
        } else {
            return redirect()->route('transaction.index')->with(['status-error' => __('Failed delete data!')]);
        }
    }

    public function print(Request $request)
    {

        if (empty($request->date1) || empty($request->date2)) {
            return redirect()->route('transaction.index')->with(['status-error' => __('Maaf ada Kesalahan')]);
        }

        $date = null;

        if (!empty($request->date1)) {
            $date = [$request->date1, $request->date2 ?? $request->date1];
        }

        $data = Transaction::with('details');

        $data = $data->whereBetween('transaksi_date', [$date[0], $date[1]]);

        $routeBack = 'transaction.index';

        $paginate = $data->paginate(100);
        $data = $paginate->items();

        $variableToView = ['data', 'date', 'routeBack', 'paginate'];

        return view('page.master.transaction.transaction-report-html')->with(compact($variableToView));
    }
}
