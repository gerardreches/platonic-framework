<?php

namespace Platonic\Framework\Settings\Trait;

/**
 * Trait Settings_Fields
 *
 * This trait provides a collection of callbacks for your add_settings_field() calls.
 *
 * All methods are public and static, so you can call them directly from anywhere.
 *
 * You can override each field callback to customize the output if you wish
 *
 * @package Platonic\Framework\Settings\Trait
 *
 * @since 1.0.0
 */
trait Settings_Fields {

	/**
	 * Call the necessary function to output the field depending on its type, and output a description if set.
	 *
	 * @param array $args
	 */
	final static function add_settings_field_callback( array $args ): void {
		if ( empty( $args['type'] ) ) {
			_doing_it_wrong(
				function_name: __METHOD__,
				message: __( "The field type is missing. Remember to set the 'type' argument.", 'platonic-framework' ),
				version: '1.0'
			);

			return;
		}

		if ( ! is_callable( array( static::class, 'add_' . $args['type'] . '_field_callback' ) ) ) {
			_doing_it_wrong(
				function_name: __METHOD__,
				message: sprintf( __( "The field type %s is not supported. Remember to set the 'type' argument to a valid field type.", 'platonic-framework' ), $args['type'] ),
				version: '1.0'
			);

			return;
		}

		call_user_func( array( static::class, 'add_' . $args['type'] . '_field_callback' ), $args );

		if ( ! empty( $args['description'] ) ) {
			echo "<p class='description'>{$args['description']}</p>";
		}
	}

	/**
	 * Output a text field.
	 *
	 * @param array $args
	 */
	static function add_text_field_callback( array $args ): void {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a textarea field.
	 *
	 * @param array $args
	 */
	static function add_textarea_field_callback( array $args ): void {
		echo "<textarea id='{$args['id']}' name='{$args['name']}' class='{$args['class']}' rows='{$args['rows']}' cols='{$args['cols']}' placeholder='{$args['placeholder']}'>{$args['value']}</textarea>";
	}

	/**
	 * Output a number field.
	 *
	 * @param array $args
	 */
	static function add_number_field_callback( array $args ): void {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='small-text {$args['class']}' step='{$args['step']}' min='{$args['min']}' max='{$args['max']}' value='{$args['value']}'>";
	}

	/**
	 * Output an email field.
	 *
	 * @param array $args
	 */
	static function add_email_field_callback( array $args ): void {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a telephone field.
	 *
	 * @param array $args
	 */
	static function add_tel_field_callback( array $args ): void {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a password field.
	 *
	 * @param array $args
	 */
	static function add_password_field_callback( array $args ): void {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a URL field.
	 *
	 * @param array $args
	 */
	static function add_url_field_callback( array $args ): void {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a color field.
	 *
	 * @param array $args
	 */
	static function add_color_field_callback( array $args ): void {
		echo "<input id='{$args['id']}' type='text' name='{$args['name']}' class='color-picker {$args['class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a checkbox field.
	 *
	 * @param array $args
	 */
	static function add_checkbox_field_callback( array $args ): void {
		$checked = checked( 1, $args['value'], false );

		echo "<input type='hidden' name='{$args['name']}' value='0'>";
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['class']}' value='1' {$checked}>";
	}

	/**
	 * Output a file selector field that uses the WordPress Media Library.
	 *
	 * @param array $args
	 */
	static function add_file_field_callback( array $args ): void {
		echo "<fieldset class='media-field {$args['class']}'>";

		echo "<img class='media-preview' src='{$args['value']}' alt='", __( 'No image selected', 'platonic-framework' ), "' onload='this.classList.remove(\"hidden\");' onerror='this.classList.add(\"hidden\");' style='max-height:96px;max-width:100%;margin-bottom: 4px;' loading='lazy' decoding='async'>";

		echo "<input type='url' class='media-url regular-text' value='{$args['value']}' disabled style='display:block;margin-bottom:4px;'>";
		echo "<input type='hidden' name='{$args['name']}' class='media-url' value='{$args['value']}'>";

		echo "<input type='button' id='{$args['id']}' class='button upload-button button-add-media' value='", __( 'Upload', 'platonic-framework' ), "'>";
		echo "<input type='button' class='button clear-button button-clear-media hidden' value='", __( 'Clear', 'platonic-framework' ), "' style='margin-left: 0.25rem;'>";

		echo "</fieldset>";
	}

	/**
	 * Output a group of radio buttons.
	 *
	 * @param array $args
	 */
	static function add_radio_field_callback( array $args ): void {
		if ( empty( $args['options'] ) ) {
			_doing_it_wrong(
				function_name: __METHOD__,
				message: __( "The 'options' argument is missing or empty. Remember to set the 'options' argument.", 'platonic-framework' ),
				version: '1.0'
			);

			return;
		}

		if ( array_keys( $args['options'] ) === range( 0, count( $args['options'] ) - 1 ) ) {
			_doing_it_wrong(
				function_name: __METHOD__,
				message: __( "The 'options' argument is an indexed array. Remember to set the 'options' argument as an associative array: \$value => \$label.", 'platonic-framework' ),
				version: '1.0'
			);
		}

		echo "<fieldset>";

		foreach ( $args['options'] as $value => $label ) {
			$selected_radio = checked( $value, $args['value'], false );
			echo "<input id='{$args['id']}[{$value}]' type='{$args['type']}' name='{$args['name']}' class='{$args['class']}' value='{$value}' {$selected_radio}>";
			echo "<label for='{$args['id']}[{$value}]' >{$label}</label><br>";
		}

		echo "</fieldset>";
	}

	/**
	 * Output a select field with its options.
	 *
	 * @param array $args
	 */
	static function add_select_field_callback( array $args ): void {
		if ( empty( $args['options'] ) ) {
			_doing_it_wrong(
				function_name: __METHOD__,
				message: __( "The 'options' argument is missing or empty. Remember to set the 'options' argument.", 'platonic-framework' ),
				version: '1.0'
			);
		}

		if ( array_keys( $args['options'] ) === range( 0, count( $args['options'] ) - 1 ) ) {
			_doing_it_wrong(
				function_name: __METHOD__,
				message: __( "The 'options' argument is an indexed array. Remember to set the 'options' argument as an associative array: \$value => \$label.", 'platonic-framework' ),
				version: '1.0'
			);
		}

		echo "<select id='{$args['id']}' name='{$args['name']}' class='{$args['class']}'>";

		foreach ( $args['options'] as $value => $label ) {
			$selected = selected( $value, $args['value'], false );
			echo "<option value='{$value}' {$selected}>{$label}</option>";
		}

		echo "</select>";
	}
}