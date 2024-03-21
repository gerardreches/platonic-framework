<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Settings_Fields_Callbacks;
use Platonic\Framework\Settings\Interface\Settings_Rules;
use Platonic\Framework\Settings\Trait\Option_Lifecycle_Manager;
use Platonic\Framework\Settings\Trait\Options_API_Wrapper;
use Platonic\Framework\Settings\Trait\Sanitization;
use Platonic\Framework\Settings\Trait\Settings_API_Wrapper;
use Platonic\Framework\Settings\Trait\Settings_Fields;

abstract class Settings implements Settings_Rules, Settings_Fields_Callbacks {

	use Settings_API_Wrapper;
	use Options_API_Wrapper;
	use Option_Lifecycle_Manager;
	use Settings_Fields;
	use Sanitization;

	/**
	 * A settings group name. Should correspond to an allowed option key name.
	 * Default allowed option key names include 'general', 'discussion',
	 * 'media', 'reading', 'writing', 'misc', 'options', and 'privacy'.
	 *
	 * @type string
	 * @note This is not the same as the option name. This value is not stored in the database.
	 */
	const OPTION_GROUP = null;

	/**
	 * Name of the option to register, sanitize, and save. Expected to not be SQL-escaped.
	 * Must be unique. Use snake_case as for WordPress Naming Conventions.
	 *
	 * @type string
	 * @note This is not the same as the option group. This value will be stored in the database.
	 */
	const OPTION_NAME = null;

	/**
	 * Default value for the option.
	 *
	 * @type array
	 */
	const DEFAULT = array();

	/**
	 * Whether to show the option in the REST API.
	 *
	 * @type bool
	 */
	const SHOW_IN_REST = false;

	/**
	 * Initialize the Settings class.
	 *
	 * @return void
	 */
	public static function initialize(): void {
		if ( empty( static::OPTION_NAME ) || ! is_string( static::OPTION_NAME ) ) {
			_doing_it_wrong( __METHOD__, __( "The constant OPTION_NAME has to be set as a non-empty string. Remember to use a unique name to avoid conflicts.", 'platonic-framework' ), '1.0' );
		}

		add_action( 'admin_init', array( static::class, 'register' ) );

		// TODO: REST API compatibility. Requires schema definition.
		//add_action( 'rest_api_init', array( static::class, 'register_settings' ) );

		/**
		 * Hook into the option's lifecycle.
		 */
		static::manage_option_lifecycle( static::OPTION_NAME );
	}

	/**
	 * Register the settings
	 *
	 * @return void
	 */
	static function register(): void {

		static::register_setting(
			option_name: static::OPTION_NAME,
			args: array(
				'type'        => 'array',
				'description' => 'An array containing multiple options',
				'default'     => static::DEFAULT ?? false
			)
		);

		static::add_settings( static::OPTION_NAME, static::DEFAULT );
	}

	/**
	 * Sanitize callback called on register_setting().
	 *
	 * @param array|null $value
	 *
	 * @return array|null
	 * @noinspection PhpUndefinedConstantInspection
	 */
	final static function sanitize_callback( mixed $value ): mixed {

		$option = str_replace( 'sanitize_option_', '', current_filter() );

		if ( PLATONIC_FRAMEWORK_DEBUG_MODE ) {
			add_settings_error( $option, 'platonic_framework_option_sanitize_callback', "Sanitize callback executed for option <em>{$option}</em> in " . static::class, 'success' );
		}

		if ( is_null( $value ) ) {
			add_settings_error( $option, $option, "Something went wrong. The value to be sanitized is null.", 'error' );

			return get_option( $option );
		}
		if ( PLATONIC_FRAMEWORK_DEBUG_MODE ) {
			add_settings_error( $option, 'platonic_framework_option_before_sanitization', "<p>Option <em>{$option}</em> before sanitization:</p><pre>" . print_r( $value, true ) . "</pre>", 'info' );
		}

		$value = static::sanitize_recursive( $value );

		if ( PLATONIC_FRAMEWORK_DEBUG_MODE ) {
			add_settings_error( $option, 'platonic_framework_option_after_sanitization', "<p>Option <em>{$option}</em> after sanitization:</p><pre>" . print_r( $value, true ) . "</pre>", 'info' );
		}

		// If non-present options were saved under the same OPTION_NAME, merge them so that they don't get deleted.
		// This is essential for when using the same OPTION_NAME in different option pages.
		$saved_options = get_option( $option );

		if ( is_array( $saved_options ) && is_array( $value ) ) {
			$value = array_merge( $saved_options, $value );
		}

		return $value;
	}
}