<?php

namespace App\Http\Controllers\Master;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Master\ProductUpload;
use App\Repositories\Master\ProductRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private static function getBreadCrumbList()
    {
        $breadCrumbList = [
            [
                'text' => 'Product Management',
                'link' => route('product.index'),
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
            'name' => [
                'title' => __('Name'),
                'show-search' => 1,
                'show-sort' => 1,
                'edit-link' => 'true',
                'default-search' => 'true',
            ],
            'code' => [
                'title' => __('Code'),
                'show-search' => 1,
                'show-sort' => 1,
                'edit-link' => 'false',
                'default-search' => 'false',
            ],
            'price' => [
                'title' => __('Price'),
                'show-search' => 0,
                'edit-link' => 'false',
                'default-search' => 'false',
            ],
            'currency' => [
                'title' => __('Currency'),
                'show-search' => 0,
                'edit-link' => 'false',
                'default-search' => 'false',
            ],
            'discount_label' => [
                'title' => __('Discount'),
                'show-search' => 0,
                'edit-link' => 'false',
                'default-search' => 'false',
            ],
            'dimension' => [
                'title' => __('Dimension'),
                'show-search' => 0,
                'edit-link' => 'false',
                'default-search' => 'false',
            ],
            'unit' => [
                'title' => __('Unit'),
                'show-search' => 0,
                'edit-link' => 'false',
                'default-search' => 'false',
            ],
            'upload_label' => [
                'title' => __('Image'),
                'image-preview' => 'true',
            ],

        ];

        return $tableKeyList;
    }


    public static function getMetaFieldSection()
    {
        $tableKey = [
            'name' => [
                'title' => __('Name'),
                'type' => 'text',
                'required' => 'true',
                'dbfield' => 'name',
                "required-placeholder" => "true",
            ],
            'code' => [
                'title' => __('Code'),
                'type' => 'text',
                'required' => 'true',
                'dbfield' => 'code',
                "required-placeholder" => "true",
            ],
            'price' => [
                'title' => __('Price'),
                'type' => 'text',
                'required' => 'true',
                'dbfield' => 'price',
            ],
            'currency' => [
                'title' => __('Currency'),
                'type' => 'text',
                'required' => 'true',
                'dbfield' => 'currency',
            ],
            'discount' => [
                'title' => __('Discount'),
                'type' => 'text',
                'required' => '',
                'dbfield' => 'discount',
            ],
            'dimension' => [
                'title' => __('Dimension'),
                'type' => 'text',
                'required' => '',
                'dbfield' => 'dimension',
            ],
            'unit' => [
                'title' => __('Unit'),
                'type' => 'text',
                'required' => '',
                'dbfield' => 'unit',
            ],
            'upload' => [
                'title' => __('Product Images'),
                'type' => 'file',
                'required' => '',
                'dbfield' => 'upload',
                'accept' => 'image/png, image/jpeg',
                'description' => '',
            ],
        ];

        return $tableKey;
    }

    public static $actionFlow =
    [];

    private static $formName = 'product';
    private static $urlParam = 'product';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page');

        $userId = auth()->user()->id;

        // $data = ProductRepository::listArrayLookup();
        // $dataArrayLookup = $data['data']['arrayLookUp'] ?? null;

        $data = ProductRepository::listProducts($request, $page);
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

        $secondaryTitle = 'All Product';

        $secondaryAction = [
            'text' => 'Create',
            'link' => route('product.create'),
        ];

        $routeName = 'product.index';
        $routeEdit = 'product.edit';
        $overrideRouteUrlParam = self::$urlParam;
        $overrideKeyId = 'id';

        $arrayLookup = [];

        $hideTitle = true;
        $filterRequest = CustomHelper::getRawQueryString($request);

        // dd(config('zcustom.api_file_url'));

        $variableToView = ['breadCrumbList', 'title', 'secondaryTitle', 'action', 'secondaryAction', 'sidebars', 'tableKeyList', 'data', 'dataFilter', 'routeName', 'routeEdit', 'overrideRouteUrlParam', 'overrideKeyId', 'arrayLookup', 'hideTitle', 'filterRequest', 'formName'];
        return view('page.master.product.index')->with(compact($variableToView));
    }

    public function create(Request $request)
    {
        $filterRequest = CustomHelper::getRawQueryString($request);

        $breadCrumbList = self::getBreadCrumbList();
        $breadCrumbList[] = [
            'text' => 'Create',
            'link' => 'product.create',
        ];

        $secondaryTitle = 'Master Product Entry';

        $secondaryAction = [
            [
                'action' => 'save',
                'text' => 'Save',
                'link' => route('product.store'),
            ],
            [
                'action' => 'link',
                'text' => 'Back',
                'link' => route('product.index', $filterRequest),
            ],
        ];

        $tableKey = self::getMetaFieldSection();
        $formName = self::$formName;

        $arrayLookup = [];

        $variableToView = ['breadCrumbList', 'secondaryTitle', 'secondaryAction', 'tableKey', 'formName', 'arrayLookup'];
        return view('page.master.product.editor')->with(compact($variableToView));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'price' => 'required',
            'currency' => 'required',
            'upload' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        $data = ProductRepository::store($request);

        if ($data['status'] != 200) {
            return redirect()->route('product.create')->with(['status-error' => __('Error! Try again later')]);
        }

        $data = $data['data']['data'];

        if ($request->save_and_new === 'yes') {
            $routeNew = 'product.create';
            return redirect()->route($routeNew)->with(['status-success' => __('Data saved!')]);
        } else {
            return redirect()->route('product.edit', [self::$urlParam => $data['id']])->with(['status-success' => __('Data saved!')]);
        }
    }

    public function edit(Request $request, string $id)
    {
        $filterRequest = CustomHelper::getRawQueryString($request);

        $data = ProductRepository::edit($id);

        $dataArrayLookup = $data['data']['arrayLookUp'] ?? null;
        $data = $data['data']['data'] ?? null;

        if (empty($data)) {
            return redirect()->route('product.index')->with(['status-error' => __('No Data!')]);
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
            'link' => 'product.edit',
        ];

        $secondaryTitle = "Product ($id)";

        $secondaryAction = [
            [
                'action' => 'save',
                'text' => 'Save',
                'link' => route('product.update', [self::$urlParam => $id]),
            ],
            [
                'action' => 'delete',
                'text' => 'Delete',
                'link' => route('product.destroy', [self::$urlParam => $id]),
                'confirm' => 'Apakah yakin',
            ],
            [
                'action' => 'action',
                'text' => 'Action',
                'lists' => $actionArray,
            ],
            [
                'action' => 'link',
                'text' => 'Back',
                'link' => route('product.index', $filterRequest),
            ],
        ];

        $tableKey = self::getMetaFieldSection();
        $formName = self::$formName;

        $arrayLookup = [];

        $arrayAttachment = [];

        $arrayAttachment['upload'] = ProductUpload::where('product_id', $data->id)->select(['file_path', 'id'])->get();

        // dd($arrayAttachment);
        $variableToView = ['breadCrumbList', 'secondaryTitle', 'secondaryAction', 'tableKey', 'formName', 'arrayLookup', 'data', 'arrayAttachment'];
        return view('page.master.product.editor')->with(compact($variableToView));
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'price' => 'required',
            'currency' => 'required',
            'upload' => 'mimes:png,jpg,jpeg,webp',
        ]);

        $data = ProductRepository::update($request, $id);

        if ($data['status'] == 200) {
            return redirect()->route('product.edit', [self::$urlParam => $id])->with(['status-success' => __('Data saved!')]);
        } else {
            return redirect()->route('product.edit', [self::$urlParam => $id])->with(['status-error' => __('Failed save data!')]);
        }
    }

    public function destroy(string $id)
    {
        $data = ProductRepository::destroy($id);

        if ($data['status'] == 200) {
            return redirect()->route('product.index')->with(['status-success' => __('Data deleted!')]);
        } else {
            return redirect()->route('product.index')->with(['status-error' => __('Failed delete data!')]);
        }
    }
}
