<?php

return [
     // Meta
     'meta' => [
          'title' => 'Gestión de Productos',
          'author' => 'Administrador',
          'description' => 'Gestionar productos para su aplicación',
          'keywords' => 'productos, gestión, administrador',
     ],

     // Management
     'management' => 'Gestión de Productos',
     'list' => 'Lista de Productos',
     'add_new' => 'Agregar Nuevo Producto',
     'add_product' => 'Agregar Producto',
     'edit_product' => 'Editar Producto',
     'product_name' => 'Nombre del Producto',
     'description' => 'Descripción',
     'price' => 'Precio',
     'quantity' => 'Cantidad',
     'category' => 'Categoría',
     'image' => 'Imagen',
     'status' => 'Estado',

     // Validation
     'validation' => [
          'name_required' => 'Por favor ingrese un nombre de producto',
          'name_minlength' => 'El nombre del producto debe tener al menos 3 caracteres',
          'name_maxlength' => 'El nombre del producto no puede exceder los 50 caracteres',
          'description_required' => 'Por favor ingrese una descripción del producto',
          'description_minlength' => 'La descripción del producto debe tener al menos 10 caracteres',
          'price_required' => 'Por favor ingrese un precio para el producto',
          'price_numeric' => 'El precio debe ser un número',
          'price_min' => 'El precio debe ser mayor que 0',
          'quantity_required' => 'Por favor ingrese una cantidad para el producto',
          'quantity_numeric' => 'La cantidad debe ser un número',
          'quantity_min' => 'La cantidad debe ser mayor o igual a 0',
          'category_required' => 'Por favor seleccione una categoría',
          'image_required' => 'Por favor seleccione una imagen',
          'image_image' => 'El archivo debe ser una imagen',
          'image_mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif',
          'image_max' => 'La imagen no puede ser mayor a 2MB',
     ],

     // Errors
     'errors' => [
          'fetch_failed' => 'Error al obtener los datos del producto',
          'fetch_error' => 'Ocurrió un error al obtener los datos del producto',
          'delete_error' => 'Ocurrió un error al eliminar el producto',
          'update_error' => 'Ocurrió un error al actualizar el producto',
          'create_error' => 'Ocurrió un error al crear el producto',
     ],

     // Delete Confirmation
     'delete' => [
          'warning' => "¡No podrás revertir esto!",
          'confirm' => '¿Estás seguro?',
          'confirm_button' => '¡Sí, elimínalo!',
     ],
];
