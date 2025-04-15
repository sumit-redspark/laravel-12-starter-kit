<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Root route
Route::get('/', function ()
{
    if (Auth::check())
    {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Language switch route
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');

// Auth routes
Route::middleware('guest')->group(function ()
{
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('custom-authenticate');
});

// Protected routes (accessible only when logged in)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function ()
{
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Permission Resource Route
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions-list', [PermissionController::class, 'list'])->name('permissions.list');

    // Role Resource Route
    Route::resource('roles', RoleController::class);
    Route::get('roles-list', [RoleController::class, 'list'])->name('roles.list');
    Route::get('roles-permissions', [RoleController::class, 'getPermissions'])->name('roles.permissions');
    Route::get('roles/{id}/permissions', [RoleController::class, 'getRolePermissions'])->name('roles.role-permissions');

    // User Resource Route
    Route::resource('users', UserController::class);
    Route::get('users-list', [UserController::class, 'list'])->name('users.list');
    Route::get('users-roles', [UserController::class, 'getRoles'])->name('users.roles');
    Route::get('users/{id}/roles', [UserController::class, 'getUserRoles'])->name('users.user-roles');

    Route::resource('products', ProductController::class);
    Route::get('products-list', [ProductController::class, 'list'])->name('products.list');
});
