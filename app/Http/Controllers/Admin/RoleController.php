<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller implements HasMiddleware
{
     public static function middleware(): array
     {
          return [
               // new Middleware('role:' . RoleEnum::SUPER_ADMIN),
               new Middleware('permission:' . PermissionEnum::ROLE_VIEW, only: ['index']),
               new Middleware('permission:' . PermissionEnum::ROLE_CREATE, only: ['store']),
               new Middleware('permission:' . PermissionEnum::ROLE_EDIT, only: ['edit', 'update']),
               new Middleware('permission:' . PermissionEnum::ROLE_DELETE, only: ['destroy']),
          ];
     }

     /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\View\View
      */
     public function index()
     {
          return view('admin.role.index');
     }

     /**
      * Get roles for DataTable.
      *
      * @return \Illuminate\Http\JsonResponse
      */
     public function list()
     {
          $currentUserId = Auth::id();
          $roles = Role::select(['id', 'name', 'guard_name', 'created_at', 'created_by'])
               ->where('name', '!=', RoleEnum::SUPER_ADMIN)
               ->orderBy('id', 'desc');

          // If not superadmin, only show roles created by current user
          if (!Auth::user()->hasRole(RoleEnum::SUPER_ADMIN))
          {
               $roles->where('created_by', $currentUserId);
          }

          return DataTables::of($roles)
               ->addColumn('action', function ($role)
               {
                    $authUser = \Illuminate\Support\Facades\Auth::user();
                    $canEdit = $authUser && \Illuminate\Support\Facades\Gate::allows(PermissionEnum::ROLE_EDIT);
                    $canDelete = $authUser && \Illuminate\Support\Facades\Gate::allows(PermissionEnum::ROLE_DELETE);

                    return view('admin.components.form-helper.datatable-actions', [
                         'id' => Crypt::encryptString($role->id),
                         'editData' => [
                              'name' => $role->name,
                              'guard' => $role->guard_name
                         ],
                         'canEdit' => $canEdit,
                         'canDelete' => $canDelete
                    ])->render();
               })
               ->addColumn('created_by_name', function ($role)
               {
                    return $role->creator ? $role->creator->name : 'System';
               })
               ->editColumn('created_at', function ($role)
               {
                    return $role->created_at->format('Y-m-d H:i:s');
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
               'name' => [
                    'required',
                    function ($attribute, $value, $fail)
                    {
                         $exists = Role::where('name', $value)
                              ->where('created_by', Auth::id())
                              ->exists();
                         if ($exists)
                         {
                              $fail('The role name has already been taken by you.');
                         }
                    }
               ],
               'permissions' => 'array'
          ]);

          try
          {
               DB::beginTransaction();

               $role = Role::create([
                    'name' => $request->name,
                    'guard_name' => 'web',
                    'created_by' => Auth::id()
               ]);

               $permissionsIds = $request->permissions ?? [];
               $permissions = Permission::whereIn('id', $permissionsIds)->get();
               $role->syncPermissions($permissions);

               DB::commit();
               return response()->json(['success' => true, 'message' => 'Role created successfully']);
          }
          catch (\Exception $e)
          {
               dd($e);
               DB::rollBack();
               return response()->json(['success' => false, 'message' => 'Failed to create role'], 500);
          }
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
               'name' => [
                    'required',
                    function ($attribute, $value, $fail) use ($decryptedId)
                    {
                         $exists = Role::where('name', $value)
                              ->where('created_by', Auth::id())
                              ->where('id', '!=', $decryptedId)
                              ->exists();
                         if ($exists)
                         {
                              $fail('The role name has already been taken by you.');
                         }
                    }
               ],
               'permissions' => 'array'
          ]);

          try
          {
               DB::beginTransaction();

               $role = Role::findOrFail($decryptedId);
               $role->update([
                    'name' => $request->name
               ]);

               $permissionsIds = $request->permissions ?? [];
               $permissions = Permission::whereIn('id', $permissionsIds)->get();
               $role->syncPermissions($permissions);

               DB::commit();
               return response()->json(['success' => true, 'message' => 'Role updated successfully']);
          }
          catch (\Exception $e)
          {
               DB::rollBack();
               return response()->json(['success' => false, 'message' => 'Failed to update role'], 500);
          }
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
          $role = Role::findOrFail($decryptedId);

          return response()->json([
               'success' => true,
               'data' => [
                    'id' => $id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'created_at' => $role->created_at
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
          $role = Role::findOrFail($decryptedId);
          $permissions = $role->permissions->pluck('id')->toArray();

          return response()->json([
               'success' => true,
               'data' => [
                    'id' => $id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions' => $permissions
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

          $role = Role::findOrFail($decryptedId);
          $role->delete();

          return response()->json(['success' => true, 'message' => 'Role deleted successfully']);
     }

     /**
      * Get all permissions for the role form.
      *
      * @return \Illuminate\Http\JsonResponse
      */
     public function getPermissions()
     {
          $permissions = Permission::select('id', 'name')->get();
          return response()->json(['success' => true, 'data' => $permissions]);
     }

     /**
      * Get permissions for a specific role.
      *
      * @param  string  $id
      * @return \Illuminate\Http\JsonResponse
      */
     public function getRolePermissions($id)
     {
          try
          {
               $decryptedId = (int) Crypt::decryptString($id);
               $role = Role::findOrFail($decryptedId);
               $permissions = $role->permissions->pluck('id')->toArray();

               return response()->json([
                    'success' => true,
                    'data' => $permissions
               ]);
          }
          catch (\Exception $e)
          {
               return response()->json(['success' => false, 'message' => 'Invalid role ID'], 400);
          }
     }
}
