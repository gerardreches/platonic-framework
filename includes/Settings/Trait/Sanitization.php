<?php

namespace Platonic\Framework\Settings\Trait;

/**
 * Trait for sanitizing and validating values using various sanitization methods.
 *
 * This trait provides methods for sanitizing and validating values using different sanitization callbacks.
 * It includes predefined methods for common data types such as text, textarea, number, email, tel, password,
 * URL, color, checkbox, file, radio, and select.
 *
 * @package Platonic\Framework\Settings\Trait
 */
trait Sanitization {

	static function sanitize_recursive( $option ) {
		return static::sanitize_by_type( static::get_wp_settings_fields_type_map(), $option );
	}

	final static function get_wp_settings_fields_type_map() {
		global $wp_settings_fields;

		$wp_settings_fields_type_map = [];

		foreach ( array_merge( ...array_values( $wp_settings_fields[ static::class ] ) ) as $field ) {

			$field_name = $field['args']['name'];
			$field_type = $field['args']['type'];

			$array_keys = explode( '[', $field_name );
			array_shift( $array_keys );

			// Use a reference to navigate through the nested structure
			$current = &$wp_settings_fields_type_map;

			// Traverse the nested array structure using the modified keys
			foreach ( $array_keys as $key ) {
				$key     = rtrim( $key, ']' );
				$current = &$current[ $key ];
			}

			// Assign the type to the leaf node
			$current = $field_type;
			unset( $current ); // Release the reference
		}

		return $wp_settings_fields_type_map;
	}

	/**
	 * Sanitizes all non-iterable elements of an array or an object according to their type.
	 *
	 * @param mixed $type
	 * @param mixed $value The array, object, or scalar.
	 * @param string|null $key
	 *
	 * @return mixed The value with the callback applied to all non-arrays and non-objects inside it.
	 * @since 4.4.0
	 * @noinspection PhpUndefinedConstantInspection
	 */
	final static function sanitize_by_type( mixed $type, mixed $value, ?string $key = null ): mixed {
		if ( is_array( $value ) ) {
			foreach ( $value as $index => $item ) {
				$value[ $index ] = static::sanitize_by_type( $type[ $index ], $item, $index );
			}
		} elseif ( is_object( $value ) ) {
			$object_vars = get_object_vars( $value );
			foreach ( $object_vars as $property_name => $property_value ) {
				$value->$property_name = static::sanitize_by_type( $type[ $property_name ], $property_value, $property_name );
			}
		} else {
			$value = static::sanitize_field( $value, $type );
			if ( PLATONIC_FRAMEWORK_DEBUG_MODE ) {
				add_settings_error( static::OPTION_NAME, 'my_option_notice', "{$key} sanitized as (" . gettype( $value ) . ") {$value}", 'success' );
			}
		}

		return $value;
	}

	/**
	 * Sanitize a value with a specified sanitization callback and return the sanitized value.
	 *
	 * @param mixed $value The value to be sanitized.
	 * @param string|null $type The input type
	 *
	 * @return mixed The sanitized value.
	 *
	 * @note sanitize_text_field() is a generic function recommended by WordPress to sanitize options arrays. We use it when there is no type defined.
	 */
	final static function sanitize_field( mixed $value, ?string $type = null ): mixed {

		if ( is_null( $type ) ) {
			return sanitize_text_field( $value );
		}

		$sanitization_callback = array( static::class, 'sanitize_' . $type );

		if ( is_callable( $sanitization_callback ) ) {
			if ( PLATONIC_FRAMEWORK_DEBUG_MODE ) {
				add_settings_error( static::OPTION_NAME, 'my_option_notice', "Sanitizing <em>(" . gettype( $value ) . ") {$value}</em> with <em>{$sanitization_callback[1]}()</em>.", 'info' );
			}

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
		return sanitize_textarea_field( $value );
	}

	/**
	 * Sanitize a number value.
	 *
	 * @param mixed $value The number value to be sanitized.
	 *
	 * @return int The sanitized number value.
	 */
	static function sanitize_number( mixed $value ): int {
		return (int) sanitize_text_field( $value );
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
		return sanitize_url( $value );
	}

	/**
	 * Sanitize a color value.
	 *
	 * @param mixed $value The color value to be sanitized.
	 *
	 * @return string|null The sanitized color value or null if invalid.
	 */
	static function sanitize_color( mixed $value ): ?string {
		return sanitize_hex_color( maybe_hash_hex_color( $value ) );
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