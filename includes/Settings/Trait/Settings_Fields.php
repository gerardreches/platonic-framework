<?php

namespace Platonic\Framework\Settings\Trait;

trait Settings_Fields {
	/**
	 * Call the necessary function to output the field depending on its type, and output a description if set.
	 *
	 * @param array $args
	 */
	final static function add_settings_field_callback( array $args ) {
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
	static function add_text_field_callback( array $args ) {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a textarea field.
	 *
	 * @param array $args
	 */
	static function add_textarea_field_callback( array $args ) {
		echo "<textarea id='{$args['id']}' name='{$args['name']}' class='{$args['field_class']}' rows='{$args['rows']}' cols='{$args['cols']}' placeholder='{$args['placeholder']}'>{$args['value']}</textarea>";
	}

	/**
	 * Output a number field.
	 *
	 * @param array $args
	 */
	static function add_number_field_callback( array $args ) {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' step='{$args['step']}' min='{$args['min']}' max='{$args['max']}' value='{$args['value']}'>";
	}

	/**
	 * Output an email field.
	 *
	 * @param array $args
	 */
	static function add_email_field_callback( array $args ) {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a telephone field.
	 *
	 * @param array $args
	 */
	static function add_tel_field_callback( array $args ) {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a password field.
	 *
	 * @param array $args
	 */
	static function add_password_field_callback( array $args ) {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' value='{$args['value']}'>";
	}

	/**
	 * Output an URL field.
	 *
	 * @param array $args
	 */
	static function add_url_field_callback( array $args ) {
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a color field.
	 *
	 * @param array $args
	 */
	static function add_color_field_callback( array $args ) {
		echo "<input id='{$args['id']}' type='text' name='{$args['name']}' class='wp-color-picker-field {$args['field_class']}' value='{$args['value']}'>";
	}

	/**
	 * Output a checkbox field.
	 *
	 * @param array $args
	 */
	static function add_checkbox_field_callback( array $args ) {
		$checked = checked( 1, $args['value'], false );

		echo "<input type='hidden' name='{$args['name']}' value='0'>";
		echo "<input id='{$args['id']}' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' value='1' {$checked}>";
	}

	/**
	 * Output a file selector field that uses WordPress' native Media Library.
	 *
	 * @param array $args
	 */
	static function add_file_field_callback( array $args ) {
		echo "<img class='media-preview' src='{$args['value']}' alt='", __( 'No image selected', 'platonic_for_generatepress' ), "' style='display:block;height:auto;width:auto;max-height:100px;margin-bottom: 0.25rem;'>";
		echo "<input type='hidden' name='{$args['name']}' id='{$args['id']}' class='regular-text media-url' value='{$args['value']}'>";
		echo "<input type='button' class='button media-browse' value='", __( 'Upload image', 'platonic_for_generatepress' ), "'>";
		echo "<input type='button' class='button media-clear' value='", __( 'Clear', 'platonic_for_generatepress' ), "' style='margin-left: 0.25rem;'>";
	}

	/**
	 * Output a group of radio buttons.
	 *
	 * @param array $args
	 */
	static function add_radio_field_callback( array $args ) {
		echo "<fieldset>";

		foreach ( $args['options'] as $value => $label ) {
			$selected_radio = checked( $value, $args['value'], false );
			echo "<input id='{$args['id']}[{$value}]' type='{$args['type']}' name='{$args['name']}' class='{$args['field_class']}' value='{$value}' {$selected_radio}>";
			echo "<label for='{$args['id']}[{$value}]' >{$label}</label><br>";
		}
		echo "</fieldset>";
	}

	/**
	 * Output a select field with its options.
	 *
	 * @param array $args
	 */
	static function add_select_field_callback( array $args ) {
		echo "<select id='{$args['id']}' name='{$args['name']}' class='{$args['field_class']}'>";

		foreach ( $args['options'] as $value => $label ) {
			$selected = selected( $value, $args['value'], false );
			echo "<option value='{$value}' {$selected}>{$label}</option>";
		}
		echo "</select>";
	}
}