<?php

return [
    // Meta
    'meta' => [
        'title' => 'Gestión de Roles',
        'author' => 'Administrador',
        'description' => 'Gestionar roles para su aplicación',
        'keywords' => 'roles, gestión, administrador',
    ],

    // Management
    'management' => 'Gestión de Roles',
    'list' => 'Lista de Roles',
    'add_new' => 'Agregar Nuevo Rol',
    'add_role' => 'Agregar Rol',
    'edit_role' => 'Editar Rol',
    'role_name' => 'Nombre del Rol',
    'permissions' => 'Permisos',
    'guard_name' => 'Nombre del Guard',

    // Validation
    'validation' => [
        'name_required' => 'Por favor ingrese un nombre de rol',
        'name_minlength' => 'El nombre del rol debe tener al menos 3 caracteres',
        'name_maxlength' => 'El nombre del rol no puede exceder los 50 caracteres',
        'permissions_required' => 'Por favor seleccione al menos un permiso',
    ],

    // Errors
    'errors' => [
        'fetch_failed' => 'Error al obtener los datos del rol',
        'fetch_error' => 'Ocurrió un error al obtener los datos del rol',
        'delete_error' => 'Ocurrió un error al eliminar el rol',
        'update_error' => 'Ocurrió un error al actualizar el rol',
        'create_error' => 'Ocurrió un error al crear el rol',
    ],

    // Delete Confirmation
    'delete' => [
        'warning' => "¡No podrás revertir esto!",
        'confirm' => '¿Estás seguro?',
        'confirm_button' => '¡Sí, elimínalo!',
    ],
];
