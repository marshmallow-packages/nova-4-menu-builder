<?php

return [
    /*
    |------------------|
    | Required options |
    |------------------|
    */


    /*
    |--------------------------------------------------------------------------
    | Table names
    |--------------------------------------------------------------------------
    */

    'menus_table_name' => 'nova_menu_menus',
    'menu_items_table_name' => 'nova_menu_menu_items',

    /*
    |--------------------------------------------------------------------------
    | You can override the table connection for validation on the Nova Menu
    | When you keep this as null we will just use your default connection.
    |--------------------------------------------------------------------------
    */
    'menus_table_connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Set all the available locales as either [key => name] pairs, a closure
    | or a callable (ie 'locales' => 'nova_get_locales').
    |
    */

    'locales' => ['en_US' => 'English'],


    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    | Set all the possible menus in a keyed array of arrays with the options
    | 'name' and optionally 'menu_item_types' and unique.
    /  Unique is true by default
    |
    | For example: ['header' => ['name' => 'Header', 'unique' => true, 'menu_item_types' => []]]
    |
    */

    'menus' => [
        'header' => [
            'name' => 'Header',
            'unique' => true,
            'max_depth' => 10,
            'menu_item_types' => []
        ],
        'footer' => [
            'name' => 'Footer',
            'unique' => true,
            'max_depth' => 10,
            'menu_item_types' => []
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu item types
    |--------------------------------------------------------------------------
    |
    | Set all the available menu item types as an array.
    |
    */

    'menu_item_types' => [
        \Marshmallow\MenuBuilder\MenuItemTypes\MenuItemTextType::class,
        \Marshmallow\MenuBuilder\MenuItemTypes\MenuItemStaticURLType::class,
    ],

    /*
    |--------------------------------|
    | Optional configuration options |
    |--------------------------------|
    */
    'show_duplicate' => true,

    'collapsed_as_default' => true,

    /*
    |--------------------------------------------------------------------------
    | Controller
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu controller.
    |
    */

    'controller' => Marshmallow\MenuBuilder\Http\Controllers\MenuController::class,


    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu resource.
    |
    */

    'resource' => Marshmallow\MenuBuilder\Nova\Resources\MenuResource::class,


    /*
    |--------------------------------------------------------------------------
    | Menu Model
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu model.
    |
    */

    'menu_model' => Marshmallow\MenuBuilder\Models\Menu::class,


    /*
    |--------------------------------------------------------------------------
    | MenuItem Model
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu Item model.
    |
    */

    'menu_item_model' => Marshmallow\MenuBuilder\Models\MenuItem::class,


    /*
    |--------------------------------------------------------------------------
    | Auto-load migrations
    |--------------------------------------------------------------------------
    |
    | Allow auto-loading of migrations (without the need to publish them)
    |
    */

    'auto_load_migrations' => true,


];
