<?php

namespace Platonic\Framework\Settings\Interface;

interface Settings_Page_Rules extends Settings_Rules {

	public function __construct();

	static function enqueue_admin_scripts( string $hook_suffix ): void;

	static function add_admin_menu(): void;

	static function add_settings( string $option_name, mixed $default ): void;

	static function create_settings_page(): void;

	static function create_form(): void;
}