<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Rules {

	public static function initialize();

	static function register(): void;

	static function add_settings_field_callback( array $args );

	static function sanitize_callback( mixed $value ): mixed;

}