<?php

namespace Platonic\Framework\Settings\Trait;

trait Option_Management {

	static function pre_update_option( mixed $value, mixed $old_value, string $option ): mixed {
		// TODO: Implement add_option() method.
		return $value;
	}

	static function after_add_option( string $option, mixed $value ): void {
		// TODO: Implement add_option() method.
	}

	static function after_update_option( mixed $old_value, mixed $value, string $option ): void {
		// TODO: Implement update_option() method.
	}

	static function after_delete_option( string $option ): void {
		// TODO: Implement add_option() method.
	}

	static function sanitize_option( mixed $value, string $option, mixed $original_value ): mixed {
		// TODO: Implement sanitize_option() method.
		return $value;
	}
}