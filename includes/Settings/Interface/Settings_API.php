<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_API {

	static function register_setting( string $option_name, array $args = array() ): void;
	static function unregister_setting( string $option_name, callable $deprecated = null ): void;

	static function add_settings_section( string $id, string $title, string $description, array $args = array() );
	static function add_settings_field( string $id, string $section, string $title, string $description, string $type, array $args );

	static function do_settings_sections(): void;
	static function do_settings_fields( string $section ): void;

	static function settings_errors( bool $sanitize = false, bool $hide_on_update = false ): void;

}