<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Settings_API;
use Platonic\Framework\Settings\Interface\Settings_Field_Callback;
use Platonic\Framework\Settings\Interface\Settings_Rules;
use Platonic\Framework\Settings\Trait\Options_API_Wrapper;
use Platonic\Framework\Settings\Trait\Settings_Fields;
use Platonic\Framework\Settings\Trait\Sanitization;
use Platonic\Framework\Settings\Trait\Option_Lifecycle_Manager;

abstract class Settings implements Settings_API, Settings_Rules, Settings_Field_Callback {

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
	 * Settings class constructor.
	 */
	public function __construct() {

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

	final static function register_setting( string $option_name, array $args = array() ): void {

		if ( array_key_exists( $option_name, get_registered_settings() ) ) {
			add_settings_error( $option_name, 'duplicated', "Setting <em>{$option_name}</em> is being registered twice. This may cause unexpected issues. Check your error log for more details.", 'warning' );
			error_log( "Class " . static::class . " is registering a setting named {$option_name} which was already registered. The setting arguments will be overwritten, which may lead to unexpected issues. Please, consider registering a setting with a different name." );
		}

		register_setting(
			option_group: static::OPTION_GROUP ?? static::OPTION_NAME,
			option_name: $option_name,
			args: array_merge(
				array(
					'sanitize_callback' => array( static::class, 'sanitize_callback' ),
					'show_in_rest'      => static::SHOW_IN_REST
				),
				$args
			)
		);
	}

	static function unregister_setting( string $option_name = null, callable $deprecated = null ): void {
		unregister_setting(
			option_group: static::OPTION_GROUP ?? static::OPTION_NAME,
			option_name: $option_name ?? static::OPTION_NAME,
			deprecated: $deprecated ?: ''
		);
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
	 * @param string $id Slug-name to identify the section. Used in the 'id' attribute of tags.
	 * @param string $title Formatted title of the section. Shown as the heading for the section.
	 * @param string|null $description Formatted description of the section. Shown as paragraph under the title.
	 * @param array $args Arguments used to create the settings section.
	 *
	 * @return string
	 */
	final static function add_settings_section( string $id, string $title, string $description = null, array $args = array() ): string {
		add_settings_section(
			id: $id,
			title: $title,
			callback: $args['callback'] ?? function ( $args ) use ( $description ) {
			echo $description ?? '';
		},
			page: static::class,
			args: $args
		);

		return $id;
	}

	/**
	 * Add a new field to a section of a settings page.
	 *
	 * Part of the Settings API. Use this to define a settings field that will show
	 * as part of a settings section inside a settings page. The fields are shown using
	 * do_settings_fields() in do_settings_sections()
	 *
	 * @param string $id Slug-name to identify the field. Used in the 'id' attribute of tags.
	 * @param string $type The type of field.
	 * @param string $title Formatted title for the field. Shown as the label for the field
	 *                           during output.
	 * @param string $description Formatted description for the field. Shown under the field
	 *                           during output.
	 * @param string $section Optional. The slug-name of the section of the settings page
	 *                           in which to show the box. Default 'default'.
	 * @param array $args {
	 *     Optional. Extra arguments used when outputting the field.
	 *
	 * @return string
	 */
	final static function add_settings_field( string $id, string $type, string $title, string $description = '', string $section = 'default', array $args = array() ): string {
		add_settings_field(
			id: $id,
			title: $title,
			callback: $args['callback'] ?? array( static::class, 'add_settings_field_callback' ),
			page: static::class,
			section: $section,
			args: array_merge(
				array(
					'label_for'   => $id,
					'description' => $description,
					'id'          => $id,
					'name'        => static::OPTION_NAME ? static::OPTION_NAME . "[{$id}]" : $id,
					'type'        => $type,
					'value'       => get_option( static::OPTION_NAME )[ $id ] ?? null,
					'default'     => null,
					'class'       => null,
					'placeholder' => null,
					'rows'        => 10,
					'cols'        => 50,
					'min'         => null,
					'max'         => null,
					'step'        => 1,
				),
				$args
			)
		);

		return $id;
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