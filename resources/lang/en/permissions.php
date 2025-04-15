<?php

return [
    'title' => 'Permissions Management',
    'meta_title' => 'Permissions Management',
    'meta_author' => 'Admin',
    'meta_description' => 'Manage permissions for your application',
    'meta_keywords' => 'permissions, management, admin',

    'management' => 'Permissions Management',
    'home' => 'Home',
    'permissions' => 'Permissions',
    'list' => 'Permissions List',
    'add_new' => 'Add New Permission',
    'name' => 'Name',
    'guard_name' => 'Guard Name',
    'created_at' => 'Created At',
    'actions' => 'Actions',
    'add_permission' => 'Add Permission',
    'permission_name' => 'Permission Name',
    'close' => 'Close',
    'save' => 'Save',
    'edit_permission' => 'Edit Permission',

    'validation' => [
        'required' => 'Please enter a permission name',
        'minlength' => 'Permission name must be at least 3 characters long',
        'maxlength' => 'Permission name cannot exceed 50 characters',
    ],

    'errors' => [
        'general' => 'An error occurred',
        'fetch_failed' => 'Failed to fetch permission data',
        'fetch_error' => 'An error occurred while fetching permission data',
        'delete_error' => 'An error occurred while deleting the permission',
    ],

    'delete' => [
        'warning' => "You won't be able to revert this!",
        'confirm' => 'Are you sure?',
        'confirm_button' => 'Yes, delete it!',
    ],
];
