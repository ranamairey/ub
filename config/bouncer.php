<?php

return [
    'model' => App\Models\User::class,
    'database' => [
        'roles_table' => 'roles',
        'permissions_table' => 'permissions',
        'users_roles_table' => 'users_roles',
        'users_permissions_table' => 'users_permissions',
    ],
];
