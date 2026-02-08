<?php

return [
    /**
     * Enum-перечисление доступных прав
     */
    'permissions_enum' => FinzorDev\Roles\Enums\DefaultPermissionEnum::class,

    /**
     * Модель роли
     */
    'role_model' => \FinzorDev\Roles\Models\Role::class,

    /**
     * Policy роли
     */
    'role_policy' => \FinzorDev\Roles\Policies\RolePolicy::class,

    /**
     * Класс модели пользователя
     */
    'user_model' => \App\Models\User::class,

    'validation' => [
        'store' => [
            'permissionsRequired' => false
        ]
    ]
];