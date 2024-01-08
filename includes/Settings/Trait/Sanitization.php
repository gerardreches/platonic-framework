<?php

namespace Platonic\Framework\Settings\Trait;

/**
 * Trait for sanitizing and validating values using various sanitization methods.
 *
 * This trait provides methods for sanitizing and validating values using different sanitization callbacks.
 * It also includes predefined methods for common data types such as text, textarea, number, email, tel, password,
 * URL, color, checkbox, file, radio, and select.
 *
 * @package Platonic\Framework\Settings\Trait
 */
trait Sanitization {

	/**
	 * Sanitize a value with a specified sanitization callback and return the sanitized value.
	 *
	 * @param mixed $value The value to be sanitized.
	 * @param callable $sanitization_callback The sanitization callback to apply.
	 *
	 * @return mixed The sanitized value.
	 */
	final static function sanitize( mixed $value, callable $sanitization_callback ): mixed {
		if ( is_callable( $sanitization_callback ) ) {
			return call_user_func( $sanitization_callback, $value );
		}

		return $value;
	}

	/**
	 * Sanitize a text value.
	 *
	 * @param mixed $value The text value to be sanitized.
	 *
	 * @return string The sanitized text value.
	 */
	static function sanitize_text( mixed $value ): string {
		return sanitize_text_field( $value );
	}

	/**
	 * Sanitize a textarea value.
	 *
	 * @param mixed $value The textarea value to be sanitized.
	 *
	 * @return string The sanitized textarea value.
	 */
	static function sanitize_textarea( mixed $value ): string {
		return sanitize_text_field( $value );
	}

	/**
	 * Sanitize a number value.
	 *
	 * @param mixed $value The number value to be sanitized.
	 *
	 * @return int The sanitized number value.
	 */
	static function sanitize_number( mixed $value ): int {
		return sanitize_text_field( (int) $value );
	}

	/**
	 * Sanitize an email value.
	 *
	 * @param mixed $value The email value to be sanitized.
	 *
	 * @return string The sanitized email value.
	 */
	static function sanitize_email( mixed $value ): string {
		return sanitize_email( $value );
	}

	/**
	 * Sanitize a telephone number value.
	 *
	 * @param mixed $value The telephone number value to be sanitized.
	 *
	 * @return array|string|null The sanitized telephone number value.
	 */
	static function sanitize_tel( mixed $value ): array|string|null {
		return preg_replace( '/[^0-9]/', '', $value );
	}

	/**
	 * Sanitize a password value.
	 *
	 * @param mixed $value The password value to be sanitized.
	 *
	 * @return mixed The sanitized password value.
	 *
	 * @note Passwords must not be altered, so the value is returned as it is.
	 */
	static function sanitize_password( mixed $value ): mixed {
		return $value;
	}

	/**
	 * Sanitize a URL value.
	 *
	 * @param mixed $value The URL value to be sanitized.
	 *
	 * @return string The sanitized URL value.
	 */
	static function sanitize_url( mixed $value ): string {
		return esc_url_raw( $value );
	}

	/**
	 * Sanitize a color value.
	 *
	 * @param mixed $value The color value to be sanitized.
	 *
	 * @return string|null The sanitized color value or null if invalid.
	 */
	static function sanitize_color( mixed $value ): ?string {
		return sanitize_hex_color( $value );
	}

	/**
	 * Sanitize a checkbox value.
	 *
	 * @param mixed $value The checkbox value to be sanitized.
	 *
	 * @return string The sanitized checkbox value.
	 */
	static function sanitize_checkbox( mixed $value ): string {
		return $value ? '1' : '0';
	}

	/**
	 * Sanitize a file value.
	 *
	 * @param mixed $value The file value to be sanitized.
	 *
	 * @return string The sanitized file value.
	 */
	static function sanitize_file( mixed $value ): string {
		return esc_url_raw( $value );
	}

	/**
	 * Sanitize a radio value.
	 *
	 * @param mixed $value The radio value to be sanitized.
	 *
	 * @return string The sanitized radio value.
	 */
	static function sanitize_radio( mixed $value ): string {
		return sanitize_text_field( $value );
	}

	/**
	 * Sanitize a select value.
	 *
	 * @param mixed $value The select value to be sanitized.
	 *
	 * @return string The sanitized select value.
	 */
	static function sanitize_select( mixed $value ): string {
		return sanitize_text_field( $value );
	}
}