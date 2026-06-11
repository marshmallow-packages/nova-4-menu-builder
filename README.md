![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")

# Nova 4 Menu Builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/nova-4-menu-builder.svg?style=flat-square)](https://packagist.org/packages/marshmallow/nova-4-menu-builder)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/nova-4-menu-builder.svg?style=flat-square)](https://packagist.org/packages/marshmallow/nova-4-menu-builder)

This [Laravel Nova](https://nova.laravel.com/) package allows you to create and manage menus and menu items.

> [!important]
> This package was originally forked from [outl1ne/nova-menu-builder](https://github.com/outl1ne/nova-menu-builder). Since we were making many opinionated changes, we decided to continue development in our own version rather than submitting pull requests that might not benefit all users of the original package. You're welcome to use this package, we're actively maintaining it. If you encounter any issues, please don't hesitate to reach out.

## Requirements

- `php: >=8.0`
- `laravel/nova: ^5.0`

This package also depends on `doctrine/dbal` and `outl1ne/nova-translations-loader`, which are installed automatically.

## Features

- Menu management
- Menu items management
  - Simple drag-and-drop nesting and re-ordering
- Custom menu item types support
  - Ability to easily add select types
  - Ability to add custom fields
  - Use the `menubuilder:type` command to easily create new types
- Fully localizable

## Installation and Setup

### Installing the package

Install the package in a Laravel Nova project via Composer, edit the configuration file and run migrations.

```bash
# Install the package
composer require marshmallow/nova-4-menu-builder

# Publish the configuration file and edit it to your preference
# NB! If you want custom table names, configure them before running the migrations.
php artisan vendor:publish --tag=nova-menu-builder-config
```

Register the tool with Nova in the `tools()` method of the `NovaServiceProvider`:

```php
// in app/Providers/NovaServiceProvider.php

public function tools()
{
    return [
        // ...
        \Marshmallow\MenuBuilder\MenuBuilder::make()
            // Optional customization
            ->title('Menus')        // Define a new name for the sidebar item
            ->icon('adjustments')   // Customize the menu icon, supports heroicons
            ->hideMenu(false),      // Hide the MenuBuilder-defined MenuSection
    ];
}
```

### Setting up

After publishing the configuration file, you have to make some changes in the config:

```php
# Choose table names of your liking by editing the two key/values:
'menus_table_name' => 'nova_menu_menus',
'menu_items_table_name' => 'nova_menu_menu_items',

# Define the locales for your project:
# If your project doesn't have localization, you can just leave it as it is.
# When there's just one locale, anything related to localization isn't displayed.
'locales' => ['en_US' => 'English'],

# Define the list of possible menus (ie 'footer', 'header', 'main-menu').
# The package ships with 'header' and 'footer' as examples:
'menus' => [
    'header' => [
        'name' => 'Header',
        'unique' => true,
        'max_depth' => 10,
        'menu_item_types' => [],
    ],
    'footer' => [
        'name' => 'Footer',
        'unique' => true,
        'max_depth' => 10,
        'menu_item_types' => [],
    ],
],

# Register the menu item types that can be selected when creating menu items.
# The package ships with a text type and a static URL type by default:
'menu_item_types' => [
    \Marshmallow\MenuBuilder\MenuItemTypes\MenuItemTextType::class,
    \Marshmallow\MenuBuilder\MenuItemTypes\MenuItemStaticURLType::class,
],
```

Next, just run the migrations and you're set.

```bash
# Run the automatically loaded migrations
php artisan migrate
```

### Optionally publish migrations

This is only useful if you want to overwrite migrations and models. If you wish to use the menu builder as it comes out of the box, you don't need them.

```bash
# Publish migrations to overwrite them (optional)
php artisan vendor:publish --tag=nova-menu-builder-migrations
```

## Configuration

The published config file lives at `config/nova-menu.php`. The available options are:

| Key | Default | Description |
| --- | --- | --- |
| `menus_table_name` | `'nova_menu_menus'` | Table name used to store menus. |
| `menu_items_table_name` | `'nova_menu_menu_items'` | Table name used to store menu items. |
| `menus_table_connection` | `null` | Override the database connection used for menu validation. Uses the default connection when `null`. |
| `locales` | `['en_US' => 'English']` | Available locales as `[key => name]` pairs, a closure or a callable. |
| `menus` | `['header' => ..., 'footer' => ...]` | The menus that can be managed, keyed by slug. Each menu supports `name`, `unique`, `max_depth` and `menu_item_types`. |
| `menu_item_types` | `[MenuItemTextType, MenuItemStaticURLType]` | The menu item types available when creating menu items. |
| `show_duplicate` | `true` | Show the duplicate action on menu items. |
| `collapsed_as_default` | `true` | Collapse nested menu items by default. |
| `controller` | `MenuController::class` | Optionally override the menu controller. |
| `resource` | `MenuResource::class` | Optionally override the Nova menu resource. |
| `menu_model` | `Menu::class` | Optionally override the menu model. |
| `menu_item_model` | `MenuItem::class` | Optionally override the menu item model. |
| `auto_load_migrations` | `true` | Auto-load the package migrations without publishing them. |

## Usage

### Locales configuration

You can define the locales for the menus in the config file, as shown below.

```php
// in config/nova-menu.php

return [
  // ...
  'locales' => [
    'en' => 'English',
    'et' => 'Estonian',
  ],

  // or using a closure (not cacheable):

  'locales' => function() {
    return nova_lang_get_locales();
  },

  // or if you want to use a function, but still be able to cache it:

  'locales' => '\App\Configuration\NovaMenuConfiguration@getLocales',

  // or

  'locales' => 'nova_lang_get_locales',
  // ...
];
```

### Custom menu item types

Menu builder allows you to create custom menu item types with custom fields.

You can scaffold a new type with the included Artisan command:

```bash
php artisan menubuilder:type
```

Alternatively, create a class that extends the `Marshmallow\MenuBuilder\MenuItemTypes\BaseMenuItemType` class and register it in the config file.

```php
// in config/nova-menu.php

return [
  // ...
  'menu_item_types' => [
    \App\MenuItemTypes\CustomMenuItemType::class,
  ],
  // ...
];
```

In the created class, overwrite the following methods:

```php
/**
 * Get the menu link identifier that can be used to tell different custom
 * links apart (ie 'page' or 'product').
 *
 * @return string
 **/
public static function getIdentifier(): string {
    // Example usecase
    // return 'page';
    return '';
}

/**
 * Get menu link name shown in  a dropdown in CMS when selecting link type
 * ie ('Product Link').
 *
 * @return string
 **/
public static function getName(): string {
    // Example usecase
    // return 'Page Link';
    return '';
}

/**
 * Get list of options shown in a select dropdown.
 *
 * Should be a map of [key => value, ...], where key is a unique identifier
 * and value is the displayed string.
 *
 * @return array
 **/
public static function getOptions($locale): array {
    // Example usecase
    // return Page::all()->pluck('name', 'id')->toArray();
    return [];
}

/**
 * Get the subtitle value shown in CMS menu items list.
 *
 * @param $value
 * @param $data The data from item fields.
 * @param $locale
 * @return string
 **/
public static function getDisplayValue($value, ?array $data, $locale) {
    // Example usecase
    // return 'Page: ' . Page::find($value)->name;
    return $value;
}

/**
 * Get the enabled value
 *
 * @param $value
 * @param $data The data from item fields.
 * @param $locale
 * @return string
*/
public static function getEnabledValue($value, ?array $data, $locale)
{
  return true;
}

/**
 * Get the value of the link visible to the front-end.
 *
 * Can be anything. It is up to you how you will handle parsing it.
 *
 * This will only be called when using the nova_get_menu()
 * and nova_get_menus() helpers or when you call formatForAPI()
 * on the Menu model.
 *
 * @param $value The key from options list that was selected.
 * @param $data The data from item fields.
 * @param $locale
 * @return any
 */
public static function getValue($value, ?array $data, $locale)
{
    return $value;
}

/**
 * Get the fields displayed by the resource.
 *
 * @return array An array of fields.
 */
public static function getFields(): array
{
    return [];
}

/**
 * Get the rules for the resource.
 *
 * @return array A key-value map of attributes and rules.
 */
public static function getRules(): array
{
    return [];
}

/**
 * Get data of the link visible to the front-end.
 *
 * Can be anything. It is up to you how you will handle parsing it.
 *
 * This will only be called when using the nova_get_menu()
 * and nova_get_menus() helpers or when you call formatForAPI()
 * on the Menu model.
 *
 * @param null $data Field values
 * @return any
 */
public static function getData($data = null)
{
    return $data;
}
```

### Returning the menus in a JSON API

#### nova_get_menus()

A helper function `nova_get_menus` is globally registered in this package which returns all the menus including their menu items in an API friendly format.

```php
public function getMenus(Request $request) {
    $menusResponse = nova_get_menus();
    return response()->json($menusResponse);
}
```

#### Get single menu via identifier

```php
// Available helpers
nova_get_menu_by_slug($menuSlug, $locale = null)
nova_get_menu_by_id($menuId, $locale = null)
```

To get a single menu, you can use the available helper functions.<br />
Returns null if no menu with the identifier is found or returns the menu if it is found. If no locale is passed, the helper will automatically choose the first configured locale.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Security Vulnerabilities

Please report security vulnerabilities by email to [stef@marshmallow.dev](mailto:stef@marshmallow.dev) rather than via the public issue tracker.

## Credits

- [Tarvo Reinpalu](https://github.com/Tarpsvo)
- [Eric Lagarda (original nova-menu-builder)](https://github.com/Krato)
- [Ralph Huwiler (vue-nestable)](https://github.com/rhwilr)
- [outl1ne/nova-menu-builder](https://github.com/outl1ne/nova-menu-builder)
- [All Contributors](https://github.com/marshmallow-packages/nova-4-menu-builder/contributors)

## License

Nova Menu Builder is open-sourced software licensed under the [MIT license](LICENSE.md).
