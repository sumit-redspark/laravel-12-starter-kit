<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Enums\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:' . Permission::USER_VIEW, only: ['index']),
            new Middleware('permission:' . Permission::USER_CREATE, only: ['create', 'store']),
            new Middleware('permission:' . Permission::USER_EDIT, only: ['edit', 'update']),
            new Middleware('permission:' . Permission::USER_DELETE, only: ['destroy']),
            // new Middleware('role:superadmin', only: ['index', 'list', 'store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Get users for DataTable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        $currentUserId = Auth::id();
        $query = User::select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'created_by'])
            // ->with(['creator:id,name'])
            ->where('id', '!=', $currentUserId);

        // If not superadmin, only show users created by current user
        if (!Auth::user()->hasRole('Super Admin'))
        {
            $query->where('created_by', $currentUserId);
        }

        $users = $query->orderBy('id', 'desc');

        return DataTables::of($users)
            ->addColumn('action', function ($user) use ($currentUserId)
            {
                $authUser = Auth::user();
                $canEdit = $authUser && \Illuminate\Support\Facades\Gate::allows(Permission::USER_EDIT);
                $canDelete = $authUser && \Illuminate\Support\Facades\Gate::allows(Permission::USER_DELETE);

                return view('admin.components.form-helper.datatable-actions', [
                    'id' => Crypt::encryptString($user->id),
                    'editData' => [
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'canEdit' => $canEdit,
                    'canDelete' => $canDelete
                ])->render();
            })
            ->addColumn('created_by_name', function ($user)
            {
                return $user->creator ? $user->creator->name : 'System';
            })
            ->editColumn('email_verified_at', function ($user)
            {
                return $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'Not Verified';
            })
            ->editColumn('created_at', function ($user)
            {
                return $user->created_at->format('Y-m-d H:i:s');
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'array'
        ]);

        try
        {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_by' => Auth::id()
            ]);

            if ($request->roles)
            {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->syncRoles($roles);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'User created successfully']);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create user'], 500);
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

        // Prevent self-update
        if ($decryptedId === Auth::id())
        {
            return response()->json(['success' => false, 'message' => 'You cannot modify your own account'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $decryptedId,
            'password' => 'nullable|string|min:8',
            'roles' => 'array'
        ]);

        try
        {
            DB::beginTransaction();

            $user = User::findOrFail($decryptedId);
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->password)
            {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            if ($request->roles)
            {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->syncRoles($roles);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update user'], 500);
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
        $user = User::findOrFail($decryptedId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at
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
        $user = User::findOrFail($decryptedId);
        $roles = $user->roles->pluck('id')->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $roles
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

        // Prevent self-deletion
        if ($decryptedId === Auth::id())
        {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account'], 403);
        }

        $user = User::findOrFail($decryptedId);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    /**
     * Get all roles for the user form.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoles()
    {
        $roles = Role::select('id', 'name')->get();
        return response()->json(['success' => true, 'data' => $roles]);
    }

    /**
     * Get roles for a specific user.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRoles($id)
    {
        try
        {
            $decryptedId = (int) Crypt::decryptString($id);
            $user = User::findOrFail($decryptedId);
            $roles = $user->roles->pluck('id')->toArray();

            return response()->json([
                'success' => true,
                'data' => $roles
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json(['success' => false, 'message' => 'Invalid user ID'], 400);
        }
    }
}
