<?php

return [
    // Meta
    'meta' => [
        'title' => 'Roles Management',
        'author' => 'Admin',
        'description' => 'Manage roles for your application',
        'keywords' => 'roles, management, admin',
    ],

    // Management
    'management' => 'Roles Management',
    'list' => 'Roles List',
    'add_new' => 'Add New Role',
    'add_role' => 'Add Role',
    'edit_role' => 'Edit Role',
    'role_name' => 'Role Name',
    'permissions' => 'Permissions',
    'guard_name' => 'Guard Name',

    // Validation
    'validation' => [
        'name_required' => 'Please enter a role name',
        'name_minlength' => 'Role name must be at least 3 characters long',
        'name_maxlength' => 'Role name cannot exceed 50 characters',
        'permissions_required' => 'Please select at least one permission',
    ],

    // Errors
    'errors' => [
        'fetch_failed' => 'Failed to fetch role data',
        'fetch_error' => 'An error occurred while fetching role data',
        'delete_error' => 'An error occurred while deleting the role',
        'update_error' => 'An error occurred while updating the role',
        'create_error' => 'An error occurred while creating the role',
    ],

    // Delete Confirmation
    'delete' => [
        'warning' => "You won't be able to revert this!",
        'confirm' => 'Are you sure?',
        'confirm_button' => 'Yes, delete it!',
    ],
];
