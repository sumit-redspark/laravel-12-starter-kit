<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class);
    Route::get('permissions-list', [App\Http\Controllers\Admin\PermissionController::class, 'list'])->name('permissions.list');

    // Role Resource Route
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    Route::get('roles-list', [App\Http\Controllers\Admin\RoleController::class, 'list'])->name('roles.list');
    Route::get('roles-permissions', [App\Http\Controllers\Admin\RoleController::class, 'getPermissions'])->name('roles.permissions');
    Route::get('roles/{id}/permissions', [App\Http\Controllers\Admin\RoleController::class, 'getRolePermissions'])->name('roles.role-permissions');

    // User Resource Route
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::get('users-list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('users.list');
    Route::get('users-roles', [App\Http\Controllers\Admin\UserController::class, 'getRoles'])->name('users.roles');
    Route::get('users/{id}/roles', [App\Http\Controllers\Admin\UserController::class, 'getUserRoles'])->name('users.user-roles');
});
