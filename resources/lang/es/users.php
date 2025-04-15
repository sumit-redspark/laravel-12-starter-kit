<?php

return [
    // Meta
    'meta' => [
        'title' => 'Gestión de Usuarios',
        'author' => 'Administrador',
        'description' => 'Gestionar usuarios para su aplicación',
        'keywords' => 'usuarios, gestión, administrador',
    ],

    // Management
    'management' => 'Gestión de Usuarios',
    'list' => 'Lista de Usuarios',
    'add_new' => 'Agregar Nuevo Usuario',
    'add_user' => 'Agregar Usuario',
    'edit_user' => 'Editar Usuario',
    'user_name' => 'Nombre de Usuario',
    'email' => 'Correo Electrónico',
    'password' => 'Contraseña',
    'confirm_password' => 'Confirmar Contraseña',
    'role' => 'Rol',
    'status' => 'Estado',

    // Validation
    'validation' => [
        'name_required' => 'Por favor ingrese un nombre de usuario',
        'name_minlength' => 'El nombre de usuario debe tener al menos 3 caracteres',
        'name_maxlength' => 'El nombre de usuario no puede exceder los 50 caracteres',
        'email_required' => 'Por favor ingrese un correo electrónico',
        'email_email' => 'Por favor ingrese un correo electrónico válido',
        'email_unique' => 'Este correo electrónico ya está en uso',
        'password_required' => 'Por favor ingrese una contraseña',
        'password_minlength' => 'La contraseña debe tener al menos 8 caracteres',
        'confirm_password_required' => 'Por favor confirme su contraseña',
        'confirm_password_same' => 'Las contraseñas no coinciden',
    ],

    // Errors
    'errors' => [
        'fetch_failed' => 'Error al obtener los datos del usuario',
        'fetch_error' => 'Ocurrió un error al obtener los datos del usuario',
        'delete_error' => 'Ocurrió un error al eliminar el usuario',
        'update_error' => 'Ocurrió un error al actualizar el usuario',
        'create_error' => 'Ocurrió un error al crear el usuario',
    ],

    // Delete Confirmation
    'delete' => [
        'warning' => "¡No podrás revertir esto!",
        'confirm' => '¿Estás seguro?',
        'confirm_button' => '¡Sí, elimínalo!',
    ],
];
