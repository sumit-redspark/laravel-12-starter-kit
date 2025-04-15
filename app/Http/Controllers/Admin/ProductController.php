<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Enums\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:' . Permission::PRODUCT_VIEW, only: ['index']),
            new Middleware('permission:' . Permission::PRODUCT_CREATE, only: ['create', 'store']),
            new Middleware('permission:' . Permission::PRODUCT_EDIT, only: ['edit', 'update']),
            new Middleware('permission:' . Permission::PRODUCT_DELETE, only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            return $this->list($request);
        }

        return view('admin.products.index');
    }

    /**
     * Get products for DataTable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $language = $request->get('language', App::getLocale());
        $products = Product::query();

        return DataTables::of($products)
            ->addColumn('row_number', function ($product)
            {
                static $rowNumber = 0;
                return ++$rowNumber;
            })
            ->addColumn('name', function ($product) use ($language)
            {
                return $product->getTranslation('name', $language, false) ?? '-';
            })
            ->addColumn('description', function ($product) use ($language)
            {
                return $product->getTranslation('description', $language, false) ?? '-';
            })
            ->addColumn('action', function ($product)
            {
                $authUser = Auth::user();
                $canEdit = $authUser && \Illuminate\Support\Facades\Gate::allows(Permission::PRODUCT_EDIT);
                $canDelete = $authUser && \Illuminate\Support\Facades\Gate::allows(Permission::PRODUCT_DELETE);

                return view('admin.components.form-helper.datatable-actions', [
                    'id' => $product->id,
                    'editData' => [
                        'name' => $product->getTranslation('name', App::getLocale(), false),
                        'description' => $product->getTranslation('description', App::getLocale(), false),
                        'price' => $product->price
                    ],
                    'canEdit' => $canEdit,
                    'canDelete' => $canDelete
                ])->render();
            })
            ->editColumn('created_at', function ($product)
            {
                return $product->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'language' => 'required|string'
        ]);

        try
        {
            DB::beginTransaction();

            $product = new Product();
            $product->setTranslation('name', $validated['language'], $validated['name']);
            $product->setTranslation('description', $validated['language'], $validated['description']);
            $product->price = $validated['price'];
            $product->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'product' => $product
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create product'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'created_at' => $product->created_at
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Product $product, Request $request)
    {
        $language = $request->get('language', App::getLocale());

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->getTranslation('name', $language, false),
                'description' => $product->getTranslation('description', $language, false),
                'price' => $product->price
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'language' => 'required|string'
        ]);

        try
        {
            DB::beginTransaction();

            // Update translations for the specified language
            $product->setTranslation('name', $validated['language'], $validated['name']);
            $product->setTranslation('description', $validated['language'], $validated['description']);

            // Update price (non-translatable field)
            $product->price = $validated['price'];
            $product->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'product' => $product
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update product'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        try
        {
            DB::beginTransaction();

            $product->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete product'], 500);
        }
    }
}
