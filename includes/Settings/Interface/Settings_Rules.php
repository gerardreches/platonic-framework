<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Rules {

	public function __construct();

	static function get_option( string $id = null, mixed $default = false ): mixed;

	static function register(): void;

	static function register_setting( string $option_name, array $args = array() ): void;

	static function add_settings_section( string $id, string $title, string $description, array $args = array() );

	static function add_settings_field( string $id, string $section, string $title, string $description, string $type, array $args );

	static function sanitize_callback( mixed $value ): mixed;

}