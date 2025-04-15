<?php

return [
     // Meta
     'meta' => [
          'title' => 'Products Management',
          'author' => 'Admin',
          'description' => 'Manage products for your application',
          'keywords' => 'products, management, admin',
     ],

     // Management
     'management' => 'Products Management',
     'list' => 'Products List',
     'add_new' => 'Add New Product',
     'add_product' => 'Add Product',
     'edit_product' => 'Edit Product',
     'product_name' => 'Product Name',
     'description' => 'Description',
     'price' => 'Price',
     'quantity' => 'Quantity',
     'category' => 'Category',
     'image' => 'Image',
     'status' => 'Status',

     // Validation
     'validation' => [
          'name_required' => 'Please enter a product name',
          'name_minlength' => 'Product name must be at least 3 characters long',
          'name_maxlength' => 'Product name cannot exceed 50 characters',
          'description_required' => 'Please enter a product description',
          'description_minlength' => 'Product description must be at least 10 characters long',
          'price_required' => 'Please enter a product price',
          'price_numeric' => 'Price must be a number',
          'price_min' => 'Price must be greater than 0',
          'quantity_required' => 'Please enter a product quantity',
          'quantity_numeric' => 'Quantity must be a number',
          'quantity_min' => 'Quantity must be greater than or equal to 0',
          'category_required' => 'Please select a category',
          'image_required' => 'Please select an image',
          'image_image' => 'File must be an image',
          'image_mimes' => 'Image must be a file of type: jpeg, png, jpg, gif',
          'image_max' => 'Image may not be greater than 2MB',
     ],

     // Errors
     'errors' => [
          'fetch_failed' => 'Failed to fetch product data',
          'fetch_error' => 'An error occurred while fetching product data',
          'delete_error' => 'An error occurred while deleting the product',
          'update_error' => 'An error occurred while updating the product',
          'create_error' => 'An error occurred while creating the product',
     ],

     // Delete Confirmation
     'delete' => [
          'warning' => "You won't be able to revert this!",
          'confirm' => 'Are you sure?',
          'confirm_button' => 'Yes, delete it!',
     ],
];
