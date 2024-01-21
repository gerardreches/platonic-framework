<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Rules {

	public function __construct();

	static function enqueue_admin_scripts( string $hook_suffix ): void;

	static function get_option( string $id = null, mixed $default = false ): mixed;

	static function register(): void;

	static function register_setting( string $option_name, array $args = array() ): void;

	static function add_settings_section( string $id, string $title, string $description, array $args = array() );

	static function add_settings_field( string $id, string $section, string $title, string $description, string $type, array $args );

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

	static function sanitize_callback( mixed $value ): mixed;

	static function create_settings_page(): void;

}