<?php

namespace App\Enums;

class Permission
{
    // User Permissions
    const USER_VIEW = 'user.view';
    const USER_CREATE = 'user.create';
    const USER_EDIT = 'user.edit';
    const USER_DELETE = 'user.delete';

    // Role Permissions
    const ROLE_VIEW = 'role.view';
    const ROLE_CREATE = 'role.create';
    const ROLE_EDIT = 'role.edit';
    const ROLE_DELETE = 'role.delete';

    // Permission Permissions
    const PERMISSION_VIEW = 'permission.view';
    const PERMISSION_CREATE = 'permission.create';
    const PERMISSION_EDIT = 'permission.edit';
    const PERMISSION_DELETE = 'permission.delete';

    // Product Permissions
    const PRODUCT_VIEW = 'product.view';
    const PRODUCT_CREATE = 'product.create';
    const PRODUCT_EDIT = 'product.edit';
    const PRODUCT_DELETE = 'product.delete';

    /**
     * Get all permissions as an array
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::USER_VIEW,
            self::USER_CREATE,
            self::USER_EDIT,
            self::USER_DELETE,
            self::ROLE_VIEW,
            self::ROLE_CREATE,
            self::ROLE_EDIT,
            self::ROLE_DELETE,
            self::PERMISSION_VIEW,
            self::PERMISSION_CREATE,
            self::PERMISSION_EDIT,
            self::PERMISSION_DELETE,
            self::PRODUCT_VIEW,
            self::PRODUCT_CREATE,
            self::PRODUCT_EDIT,
            self::PRODUCT_DELETE,
        ];
    }

    /**
     * Get all user-related permissions
     *
     * @return array
     */
    public static function userPermissions(): array
    {
        return [
            self::USER_VIEW,
            self::USER_CREATE,
            self::USER_EDIT,
            self::USER_DELETE,
        ];
    }

    /**
     * Get all role-related permissions
     *
     * @return array
     */
    public static function rolePermissions(): array
    {
        return [
            self::ROLE_VIEW,
            self::ROLE_CREATE,
            self::ROLE_EDIT,
            self::ROLE_DELETE,
        ];
    }

    /**
     * Get all permission-related permissions
     *
     * @return array
     */
    public static function permissionPermissions(): array
    {
        return [
            self::PERMISSION_VIEW,
            self::PERMISSION_CREATE,
            self::PERMISSION_EDIT,
            self::PERMISSION_DELETE,
        ];
    }

    /**
     * Get all product-related permissions
     *
     * @return array
     */
    public static function productPermissions(): array
    {
        return [
            self::PRODUCT_VIEW,
            self::PRODUCT_CREATE,
            self::PRODUCT_EDIT,
            self::PRODUCT_DELETE,
        ];
    }
}
