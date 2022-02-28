<?php

return [

    'name' => 'Laravel Shop',

    'logo' => '<b>Laravel</b> Shop',

    'logo-mini' => '<b>LS</b>',

    'bootstrap' => app_path('Admin/bootstrap.php'),

    'route' => [
        'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),
        'namespace' => 'App\\Admin\\Controllers',
        'middleware' => ['web', 'admin'],
    ],

    'directory' => app_path('Admin'),

    'title' => 'Laravel Shop 管理后台',

    'secure' => env('ADMIN_HTTPS', false),

    'auth' => [

        'controller' => App\Admin\Controllers\AuthController::class,

        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => Encore\Admin\Auth\Database\Administrator::class,
            ],
        ],

        'remember' => true,

        'redirect_to' => 'auth/login',

        'excepts' => [
            'auth/login',
            'auth/logout',
            '_handle_action_',
        ]
    ],

    'upload' => [
        'disk' => 'public',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    'database' => [

        'connection' => '',

        'users_table' => 'admin_users',
        'users_model' => Encore\Admin\Auth\Database\Administrator::class,

        'roles_table' => 'admin_roles',
        'roles_model' => Encore\Admin\Auth\Database\Role::class,

        'permissions_table' => 'admin_permissions',
        'permissions_model' => Encore\Admin\Auth\Database\Permission::class,

        'menu_table' => 'admin_menu',
        'menu_model' => Encore\Admin\Auth\Database\Menu::class,

        'operation_log_table'    => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table'       => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table'        => 'admin_role_menu',
    ],

    'operation_log' => [

        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        'enable' => true,

        'except' => [
            'admin/auth/logs*',
        ],
    ],

    'check_route_permission' => true,

    'check_menu_roles'       => true,

    'default_avatar' => '/vendor/laravel-admin/AdminLTE/dist/img/user2-160x160.jpg',

    'map_provider' => 'google',

    'skin' => 'skin-blue-light',

    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    'login_background_image' => '',

    'show_version' => true,

    'show_environment' => true,

    'menu_bind_permission' => true,

    'enable_default_breadcrumb' => true,

    'minify_assets' => [
        'excepts' => [],
    ],

    'enable_menu_search' => true,

    'top_alert' => '',

    'grid_action_class' => \Encore\Admin\Grid\Displayers\DropdownActions::class,

    'extension_dir' => app_path('Admin/Extensions'),

    'extensions' => [
        'quill' => [
            'enable' => true,
            'config' => [
                'modules' => [
                    'syntax' => true,
                    'toolbar' =>
                    [
                        ['size' => []],
                        ['header' => []],
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        ['script' => 'super'],
                        ['script' => 'sub'],
                        ['color' => []],
                        ['background' => []],
                        'blockquote',
                        'code-block',
                        ['list' => 'ordered'],
                        ['list' => 'bullet'],
                        ['indent' => '-1'],
                        ['indent' => '+1'],
                        'direction',
                        ['align' => []],
                        'link',
                        'image',
                        'video',
                        'formula',
                        'clean'
                    ],
                ],
                'theme' => 'snow',
                'height' => '200px',
            ]
        ]
    ]
];
