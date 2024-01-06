<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Rules {

	public function __construct();

	static function enqueue_admin_scripts();

	static function get_options();

	static function get_option( string $id, $default );

	static function add_options_page( string $page_title, string $menu_title, string $capability, int $position );

	static function add_menu_page( string $page_title, string $menu_title, string $icon_url, string $capability, int $position );

	static function add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, int $position );

	function register_settings();

	static function add_settings_section( string $id, string $title, string $description );

	function add_settings_field( string $id, string $section, string $title, string $description, string $type, array $args );

	static function add_settings_field_callback( array $args );

	static function add_text_field_callback( array $args );

	static function add_textarea_field_callback( array $args );

	static function add_number_field_callback( array $args );

	static function add_email_field_callback( array $args );

	static function add_tel_field_callback( array $args );

	static function add_password_field_callback( array $args );

	static function add_url_field_callback( array $args );

	static function add_color_field_callback( array $args );

	static function add_checkbox_field_callback( array $args );

	static function add_file_field_callback( array $args );

	static function add_radio_field_callback( array $args );

	static function add_select_field_callback( array $args );

	function sanitize_callback( array $options );

	static function sanitize( $value, callable $sanitization_callback );

	static function sanitize_text( $value );

	static function sanitize_number( $value );

	static function sanitize_email( $value );

	static function sanitize_tel( $value );

	static function sanitize_password( $value );

	static function sanitize_url( $value );

	static function sanitize_color( $value );

	static function sanitize_checkbox( $value );

	static function sanitize_radio( $value );

	static function sanitize_file( $value );

	static function sanitize_select( $value );

	static function create_settings_page();

}