<?php

namespace Platonic\Framework\Settings\Trait;

trait Options_Page {

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
	final static function add_options_page( string $page_title, string $menu_title, string $capability = 'manage_options', int $position = null ) {
		return add_options_page(
			$page_title,
			$menu_title,
			$capability,
			static::MENU_SLUG,
			array( static::class, 'create_settings_page' ),
			$position ?? static::MENU_POSITION
		);
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
	final static function add_menu_page( string $page_title, string $menu_title, string $icon_url = '', string $capability = 'manage_options', int $position = null ) {
		return add_menu_page(
			$page_title,
			$menu_title,
			$capability,
			static::MENU_SLUG,
			array( static::class, 'create_settings_page' ),
			$icon_url,
			$position ?? static::MENU_POSITION
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
	final static function add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability = 'manage_options', int $position = null ) {
		return add_submenu_page(
			$parent_slug,
			$page_title,
			$menu_title,
			$capability,
			static::MENU_SLUG,
			array( static::class, 'create_settings_page' ),
			$position ?? static::MENU_POSITION
		);
	}
}