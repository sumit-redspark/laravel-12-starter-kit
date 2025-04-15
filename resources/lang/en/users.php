<?php

return [
    // Meta
    'meta' => [
        'title' => 'Users Management',
        'author' => 'Admin',
        'description' => 'Manage users for your application',
        'keywords' => 'users, management, admin',
    ],

    // Management
    'management' => 'Users Management',
    'list' => 'Users List',
    'add_new' => 'Add New User',
    'add_user' => 'Add User',
    'edit_user' => 'Edit User',
    'user_name' => 'User Name',
    'email' => 'Email',
    'password' => 'Password',
    'confirm_password' => 'Confirm Password',
    'role' => 'Role',
    'status' => 'Status',

    // Validation
    'validation' => [
        'name_required' => 'Please enter a user name',
        'name_minlength' => 'User name must be at least 3 characters long',
        'name_maxlength' => 'User name cannot exceed 50 characters',
        'email_required' => 'Please enter an email address',
        'email_email' => 'Please enter a valid email address',
        'email_unique' => 'This email address is already in use',
        'password_required' => 'Please enter a password',
        'password_minlength' => 'Password must be at least 8 characters long',
        'confirm_password_required' => 'Please confirm your password',
        'confirm_password_same' => 'Passwords do not match',
    ],

    // Errors
    'errors' => [
        'fetch_failed' => 'Failed to fetch user data',
        'fetch_error' => 'An error occurred while fetching user data',
        'delete_error' => 'An error occurred while deleting the user',
        'update_error' => 'An error occurred while updating the user',
        'create_error' => 'An error occurred while creating the user',
    ],

    // Delete Confirmation
    'delete' => [
        'warning' => "You won't be able to revert this!",
        'confirm' => 'Are you sure?',
        'confirm_button' => 'Yes, delete it!',
    ],
];
