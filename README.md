# Platonic Settings API

The __Platonic Settings API__ is an OOP (Object-oriented Programming) solution to create your own settings pages through the [WordPress' Settings API](https://codex.wordpress.org/Settings_API).

Basic examples are provided to guide you through the use of this API and the creation of your first settings pages.





## Installation method

You have two different methods to start using the __Platonic Settings API__. You can choose if you want to install the WordPress plugin (recommended) or to include it as part of the plugin/theme you are developing.

### Option 1: Install the plugin (recommended)

This is the easiest method, and it will allow you to receive updates as well as not having to include the files multiple times if you have multiple plugins and/or themes using the __Platonic Settings API__.

### Option 2: Include it in your plugin or theme

1. Download this library and include it in your project.
2. (Manual installation only) Require the `platonic-settings-api.php` file

## Get Started

1. Create your PHP class
2. Extend your class with the `Platonic\API\Theme_Settings` or the `Platonic\API\Plugin_Settings`

## Methods

The __Platonic Settings API__ includes some wrapper functions around some of the WordPress functions used to create your custom settings pages.

```php
add_options_page( $page_title, $menu_title, $capability = 'manage_options', $position = null )
add_menu_page( $page_title, $menu_title, $icon_url = '', $capability = 'manage_options', $position = null )
add_submenu_page( $parent_slug, $page_title, $menu_title, $capability = 'manage_options', $position = null )
```

## Contribute

To contribute to this library, please send a pull request.

### TO DO

- [x] Compatible with array options.
- [ ] Compatible with individual options.
- [ ] Register multiple individual settings in a single class.
- [ ] Allow registering settings as _options_ or _theme_mods_.
- [ ] REST API compatibility. Requires schema definition for each field.