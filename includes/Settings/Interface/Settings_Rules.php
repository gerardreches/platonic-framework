<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Rules {

	public function __construct();

	static function enqueue_admin_scripts();

	static function get_options();

	static function get_option( string $id, $default );

	function register_settings(): void;

	function add_settings(): void;

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

	static function create_settings_page(): void;

}