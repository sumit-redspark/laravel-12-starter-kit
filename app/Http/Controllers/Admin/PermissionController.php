<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Enums\Permission as PermissionEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:Super Admin'),
            new Middleware('permission:' . PermissionEnum::PERMISSION_VIEW, only: ['index']),
            new Middleware('permission:' . PermissionEnum::PERMISSION_CREATE, only: ['store']),
            new Middleware('permission:' . PermissionEnum::PERMISSION_EDIT, only: ['edit', 'update']),
            new Middleware('permission:' . PermissionEnum::PERMISSION_DELETE, only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.permission.index');
    }

    /**
     * Get permissions for DataTable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $permissions = Permission::select(['id', 'name', 'guard_name', 'created_at'])->orderBy('id', 'desc');

        return DataTables::of($permissions)
            ->addColumn('action', function ($permission)
            {
                $authUser = \Illuminate\Support\Facades\Auth::user();
                $canEdit = $authUser && \Illuminate\Support\Facades\Gate::allows(PermissionEnum::PERMISSION_EDIT);
                $canDelete = $authUser && \Illuminate\Support\Facades\Gate::allows(PermissionEnum::PERMISSION_DELETE);

                return view('admin.components.form-helper.datatable-actions', [
                    'id' => Crypt::encryptString($permission->id),
                    'editData' => [
                        'name' => $permission->name,
                        'guard' => $permission->guard_name
                    ],
                    'canEdit' => $canEdit,
                    'canDelete' => $canDelete
                ])->render();
            })
            ->editColumn('created_at', function ($permission)
            {
                return $permission->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return response()->json(['success' => true, 'message' => 'Permission created successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $decryptedId = (int) Crypt::decryptString($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $decryptedId
        ]);

        $permission = Permission::findOrFail($decryptedId);
        $permission->update([
            'name' => $request->name
        ]);

        return response()->json(['success' => true, 'message' => 'Permission updated successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $decryptedId = (int) Crypt::decryptString($id);
        $permission = Permission::findOrFail($decryptedId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $decryptedId = (int) Crypt::decryptString($id);
        $permission = Permission::findOrFail($decryptedId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $decryptedId = (int) Crypt::decryptString($id);

        $permission = Permission::findOrFail($decryptedId);
        $permission->delete();

        return response()->json(['success' => true, 'message' => 'Permission deleted successfully']);
    }
}
