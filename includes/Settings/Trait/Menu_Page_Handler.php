<?php

namespace Platonic\Framework\Settings\Trait;

/**
 * Trait providing methods to add options pages to the WordPress admin menu.
 *
 * This trait encapsulates functions to add top-level menu pages and submenu pages to the WordPress admin menu.
 * It allows for the creation of settings pages associated with those menu items.
 *
 * @package Platonic\Framework\Settings\Trait
 */
trait Menu_Page_Handler {

	/**
	 * @var array Stores the resulting page's hook_suffix for each class using this Trait.
	 *
	 * @note Don't use this property directly. Use the static methods instead.
	 */
	private static array $page_hook_suffix = array();

	final static function get_page_hook_suffix( string $class_name = null ): string {
		return self::$page_hook_suffix[ $class_name ?? static::class ];
	}

	/**
	 * Add a top-level menu page.
	 *
	 * This function takes a capability which will be used to determine whether a page is included in the menu.
	 *
	 * The function which is hooked in to handle the output of the page must check
	 * that the user has the required capability as well.
	 *
	 * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string $menu_title The text to be used for the menu.
	 * @param string $icon_url The URL to the icon to be used for this menu.
	 *                             * Pass a base64-encoded SVG using a data URI, which will be colored to match
	 *                               the color scheme. This should begin with 'data:image/svg+xml;base64,'.
	 *                             * Pass the name of a Dashicons helper class to use a font icon,
	 *                               e.g. 'dashicons-chart-pie'.
	 *                             * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 * @param string $capability The capability required for this menu to be displayed to the user.
	 * @param int|null $position The position in the menu order this item should appear.
	 *
	 * @return string The resulting page's hook_suffix.
	 * @since 1.0
	 */
	final static protected function add_menu_page( string $page_title, string $menu_title, string $icon_url = '', string $capability = 'manage_options', int $position = null ): string {

		return self::$page_hook_suffix[ static::class ] = add_menu_page(
			page_title: $page_title,
			menu_title: $menu_title,
			capability: $capability,
			menu_slug: static::MENU_SLUG ?? sanitize_title( static::class ),
			callback: array( static::class, 'create_settings_page' ),
			icon_url: $icon_url,
			position: $position ?? static::MENU_POSITION
		);
	}

	/**
	 * Add a submenu page.
	 *
	 * This function takes a capability which will be used to determine whether a page is included in the menu.
	 *
	 * The function which is hooked in to handle the output of the page must check
	 * that the user has the required capability as well.
	 *
	 * @param string $parent_slug The slug name for the parent menu (or the file name of a standard
	 *                              WordPress admin page).
	 * @param string $page_title The text to be displayed in the title tags of the page when the menu
	 *                              is selected.
	 * @param string $menu_title The text to be used for the menu.
	 * @param string $capability The capability required for this menu to be displayed to the user.
	 * @param int|null $position The position in the menu order this item should appear.
	 *
	 * @return string|false The resulting page's hook_suffix, or false if the user does not have the capability required.
	 * @since 1.0
	 */
	final static protected function add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability = 'manage_options', int $position = null ): string|false {
		return self::$page_hook_suffix[ static::class ] = add_submenu_page(
			parent_slug: $parent_slug,
			page_title: $page_title,
			menu_title: $menu_title,
			capability: $capability,
			menu_slug: static::MENU_SLUG ?? sanitize_title( static::class ),
			callback: array( static::class, 'create_settings_page' ),
			position: $position ?? static::MENU_POSITION
		);
	}

	/**
	 * Add submenu page to the Settings main menu.
	 *
	 * This function takes a capability which will be used to determine whether a page is included in the menu.
	 *
	 * The function which is hooked in to handle the output of the page must check
	 * that the user has the required capability as well.
	 *
	 * @param string $page_title The text to be displayed in the title tags of the page when the menu is selected.
	 * @param string $menu_title The text to be used for the menu.
	 * @param string $capability The capability required for this menu to be displayed to the user.
	 * @param int|null $position The position in the menu order this item should appear.
	 *
	 * @return string|false The resulting page's hook_suffix, or false if the user does not have the capability required.
	 * @since 1.0
	 */
	final static protected function add_options_page( string $page_title, string $menu_title, string $capability = 'manage_options', int $position = null ): string|false {
		return self::$page_hook_suffix[ static::class ] = add_options_page(
			page_title: $page_title,
			menu_title: $menu_title,
			capability: $capability,
			menu_slug: static::MENU_SLUG ?? sanitize_title( static::class ),
			callback: array( static::class, 'create_settings_page' ),
			position: $position ?? static::MENU_POSITION
		);
	}

}
