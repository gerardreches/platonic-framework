<?php

namespace Platonic\Framework\Settings\Trait;

use Platonic\Admin\Modules\Colors_Settings_Page;

/**
 * Trait Settings_API_Wrapper
 *
 * This trait provides a wrapper for the WordPress Settings API.
 *
 * @package Platonic\Framework\Settings\Trait
 *
 * @since 1.0.0
 */
trait Settings_API_Wrapper {

	/**
	 * Registers a setting and its data.
	 *
	 * @param string|null $option_name The name of an option to sanitize and save.
	 * @param array $args {
	 *     Optional. An array of data used to describe the setting when registered. Default empty array.
	 *
	 * @return void
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_setting/
	 *
	 * @note This does not add the option to the database.
	 */
	final static function register_setting( string $option_name = null, array $args = array() ): void {

		if ( array_key_exists( $option_name ?? static::OPTION_NAME, get_registered_settings() ) ) {
			_doing_it_wrong(
				function_name: __METHOD__,
				message: sprintf( __( 'Class %s is trying to register a setting named %s which was already registered. Make sure you are not registering the setting twice and that your setting name is unique and does not conflict with other themes or plugins.', 'platonic-framework' ), static::class, $option_name ?? static::OPTION_NAME ),
				version: '1.0'
			);

			return;
		}

		register_setting(
			option_group: static::OPTION_GROUP ?? static::OPTION_NAME,
			option_name: $option_name ?? static::OPTION_NAME,
			args: array_merge(
				array(
					'sanitize_callback' => array( static::class, 'sanitize_callback' ),
					'show_in_rest'      => static::SHOW_IN_REST
				),
				$args
			)
		);
	}

	/**
	 * Unregisters a setting.
	 *
	 * @param string|null $option_name The name of the option to unregister.
	 * @param callable|string $deprecated Deprecated.
	 *
	 * @return void
	 *
	 * @see https://developer.wordpress.org/reference/functions/unregister_setting/
	 *
	 * @note This does not delete the option from the database.
	 */
	final protected static function unregister_setting( string $option_name = null, callable|string $deprecated = '' ): void {
		unregister_setting(
			option_group: static::OPTION_GROUP ?? static::OPTION_NAME,
			option_name: $option_name ?? static::OPTION_NAME,
			deprecated: $deprecated
		);
	}

	/**
	 * Add a new section to a settings page.
	 *
	 * @param string $id Slug-name to identify the section. Used in the 'id' attribute of tags.
	 * @param string $title Formatted title of the section. Shown as the heading for the section.
	 * @param string|null $description Description of the section. Shown under the section heading.
	 * @param callable|null $callback Function that echos out any content at the top of the section (between heading and fields).
	 * @param array $args {
	 *     Optional. Arguments used to create the settings section.
	 *
	 * @type string $before_section HTML content to prepend to the section’s HTML output. Receives the section’s class name as %s.
	 * @type string $after_section HTML content to append to the section’s HTML output.
	 * @type string $section_class The class name to use for the section.
	 * }
	 *
	 * @return string The $id param.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
	 *
	 * @note You can store in a variable the value returned and use it later as the $section parameter of add_settings_field().
	 */
	final static function add_settings_section( string $id, string $title, string $description = null, callable $callback = null, array $args = array() ): string {
		add_settings_section(
			id: $id,
			title: $title,
			callback: $callback ?? function ( $args ) use ( $description ) {
			echo $description ?? '';
		},
			page: static::class,
			args: $args
		);

		return $id;
	}

	/**
	 * Adds a new field to a section of a settings page.
	 *
	 * @param string $id Slug-name to identify the field. Used in the 'id' attribute of tags and as part of the 'name' attribute of tags.
	 * @param string $type Type of settings field. See add_settings_field() for accepted types.
	 * @param string $title Formatted title of the field. Shown as the label for the field.
	 * @param string|null $description Description of the field. Shown beneath the field input, wrapped in a p tag.
	 * @param string $section The slug-name of the section of the settings page in which to show the box. Default 'default'.
	 * @param array $args {
	 *     Optional. Array of arguments to control behavior of the settings field.
	 *
	 * @type callable $callback Function that fills the field with the desired form inputs. The function should echo its output.
	 * @type string $default The default value of the field.
	 * @type string $class The class of the field.
	 * @type string $placeholder The placeholder of the field.
	 * @type array $options Only for radio or select fields. Must be an associative array where the array key is the option value, and the array value is the option label.
	 * @type int $rows The number of rows of the field. Only for textarea fields.
	 * @type int $cols The number of columns of the field. Only for textarea fields.
	 * @type int $min The minimum value of the field. Only for number fields.
	 * @type int $max The maximum value of the field. Only for number fields.
	 * @type int $step The step value of the field. Only for number fields.
	 * }
	 *
	 * @return string The $id param.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
	 */
	final static function add_settings_field( string $id, string $type, string $title, string $description = null, string $section = 'default', array $args = array() ): string {
		add_settings_field(
			id: $id,
			title: $title,
			callback: $args['callback'] ?? array( static::class, 'add_settings_field_callback' ),
			page: static::class,
			section: $section,
			args: array_merge(
				array(
					'label_for'   => $id,
					'description' => $description ?? '',
					'id'          => $id,
					'name'        => static::OPTION_NAME ? static::OPTION_NAME . "[{$id}]" : $id,
					'type'        => $type,
					'value'       => static::get_option()[ $id ] ?? $args['default'] ?? static::DEFAULT[ $id ] ?? null,
					'default'     => static::DEFAULT[ $id ] ?? null,
					'class'       => null,
					'placeholder' => null,
					'options'	  => array(),
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

}