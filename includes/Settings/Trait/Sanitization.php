<?php

namespace Platonic\Framework\Settings\Trait;

trait Sanitization {
	/**
	 * Sanitize a value with its sanitization callback and return the sanitized value.
	 *
	 * @param $value
	 * @param callable $sanitization_callback
	 *
	 * @return array
	 */
	final static function sanitize( $value, callable $sanitization_callback ) {
		if ( is_callable( $sanitization_callback ) ) {
			return call_user_func( $sanitization_callback, $value );
		}

		return $value;
	}

	static function sanitize_text( $value ) {
		return sanitize_text_field( $value );
	}

	static function sanitize_textarea( $value ) {
		return sanitize_text_field( $value );
	}

	static function sanitize_email( $value ) {
		return sanitize_email( $value );
	}

	static function sanitize_tel( $value ) {
		return preg_replace( '/[^0-9]/', '', $value );
	}

	static function sanitize_password( $value ) {
		return $value;
	}

	static function sanitize_url( $value ) {
		return esc_url_raw( $value );
	}

	static function sanitize_color( $value ) {
		return sanitize_hex_color( $value );
	}

	static function sanitize_checkbox( $value ) {
		return $value ? '1' : '0';
	}

	static function sanitize_file( $value ) {
		return esc_url_raw( $value );
	}

	static function sanitize_radio( $value ) {
		return sanitize_text_field( $value );
	}

	static function sanitize_select( $value ) {
		return sanitize_text_field( $value );
	}
}