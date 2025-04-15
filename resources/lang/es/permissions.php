<?php

return [
    'title' => 'Gestión de Permisos',
    'meta_title' => 'Gestión de Permisos',
    'meta_author' => 'Administrador',
    'meta_description' => 'Gestionar permisos para su aplicación',
    'meta_keywords' => 'permisos, gestión, administrador',

    'management' => 'Gestión de Permisos',
    'home' => 'Inicio',
    'permissions' => 'Permisos',
    'list' => 'Lista de Permisos',
    'add_new' => 'Agregar Nuevo Permiso',
    'name' => 'Nombre',
    'guard_name' => 'Nombre del Guard',
    'created_at' => 'Creado En',
    'actions' => 'Acciones',
    'add_permission' => 'Agregar Permiso',
    'permission_name' => 'Nombre del Permiso',
    'close' => 'Cerrar',
    'save' => 'Guardar',
    'edit_permission' => 'Editar Permiso',

    'validation' => [
        'required' => 'Por favor ingrese un nombre de permiso',
        'minlength' => 'El nombre del permiso debe tener al menos 3 caracteres',
        'maxlength' => 'El nombre del permiso no puede exceder los 50 caracteres',
    ],

    'errors' => [
        'general' => 'Ocurrió un error',
        'fetch_failed' => 'Error al obtener los datos del permiso',
        'fetch_error' => 'Ocurrió un error al obtener los datos del permiso',
        'delete_error' => 'Ocurrió un error al eliminar el permiso',
    ],

    'delete' => [
        'warning' => "¡No podrás revertir esto!",
        'confirm' => '¿Estás seguro?',
        'confirm_button' => '¡Sí, elimínalo!',
    ],
];
