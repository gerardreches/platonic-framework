<?php

namespace Platonic\Api\Settings\Example;

use Platonic\Api\Settings\Interface\ThemeSettingsPageRules;
use Platonic\Api\Settings\ThemeSettings;

/**
 * INTRODUCTION
 *
 * This is an example of how to create your own settings page
 * in a simplified way using the Platonic Settings API.
 *
 * The Platonic Settings API is a wrapper for the WordPress Settings API,
 * based on an Object-Oriented Programming approach.
 *
 * You would replace the name of this class with your own,
 * extend the Platonic\Api\Settings\ThemeSettings class, and
 * implement the ThemeSettingsPageRules interface.
 *
 * This example class contains some methods that are 100% optional.
 * These methods are advanced usage examples, and they are not required
 * at all, so feel free to skip them. Don't let them overwhelm you,
 * it is really easy to create your settings page with the Platonic Settings API.
 *
 * There are only 2 required methods: add_admin_menu() and register_settings()
 *
 */
class ThemeSettingsPage extends ThemeSettings implements ThemeSettingsPageRules {

	const OPTION_GROUP = 'your_option_group';
	const OPTION_NAME = 'your_option_name';

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		$this->add_options_page(
			__( 'Page Title', 'your_text_domain' ),
			__( 'Menu Title', 'your_text_domain' )
		);
	}

	public function register_settings() {
		// The parent class will register the settings in the database.
		parent::register_settings();

		// Register your sections and fields
		$this->add_settings_section(
			'first_section',
			__( 'First Section Title', 'your_text_domain' ),
			__( 'This is the description for this section', 'your_text_domain' )
		);

		$this->add_settings_field(
			'text_field_example',
			'first_section',
			__( 'Text Field Title', 'your_text_domain' ),
			__( 'Description for your text field.', 'your_text_domain' ),
			'text'
		);

		$this->add_settings_field(
			'email_field_example',
			'first_section',
			__( 'Email Field Title', 'your_text_domain' ),
			__( 'Description for your email field.', 'your_text_domain' ),
			'email'
		);

		$this->add_settings_field(
			'color_field_example',
			'first_section',
			__( 'Color Field Title', 'your_text_domain' ),
			__( 'Description for your color field.', 'your_text_domain' ),
			'color'
		);

		$this->add_settings_field(
			'checkbox_field_example',
			'first_section',
			__( 'Checkbox Field Title', 'your_text_domain' ),
			__( 'Description for your checkbox field.', 'your_text_domain' ),
			'checkbox'
		);

		$this->add_settings_section(
			'second_section',
			__( 'Second Section Title', 'your_text_domain' ),
			__( 'This is the description for this section', 'your_text_domain' )
		);

		$this->add_settings_field(
			'file_example',
			'second_section',
			__( 'File Field Title', 'your_text_domain' ),
			__( 'Description for your file selector.', 'your_text_domain' ),
			'file'
		);

		$this->add_settings_field(
			'radio_example',
			'second_section',
			__( 'Radio Buttons Title', 'your_text_domain' ),
			__( 'Description for your radio buttons.', 'your_text_domain' ),
			'radio',
			array(
				'options' => array(
					'first_value'  => __( 'Label for your first radio button', 'your_text_domain' ),
					'second_value' => __( 'Label for your second radio button', 'your_text_domain' ),
					'third_value'  => __( 'Label for your third radio button', 'your_text_domain' )
				)
			)
		);

		$this->add_settings_field(
			'select_example',
			'second_section',
			__( 'Select Title', 'your_text_domain' ),
			__( 'Description for your select dropdown.', 'your_text_domain' ),
			'select',
			array(
				'options' => array(
					'first_value'  => __( 'Label for your first option', 'your_text_domain' ),
					'second_value' => __( 'Label for your second option', 'your_text_domain' ),
					'third_value'  => __( 'Label for your third option', 'your_text_domain' )
				)
			)
		);
	}

	/**
	 * The on_theme_activation method is run when the blog’s theme is changed.
	 * Specifically, it is executed after the theme has been switched but before the next request.
	 * You would use this method to do things when the theme is activated.
	 */
	function on_theme_activation( $new_theme ) {
		// TODO: Implement on_theme_activation() method.

		$updated_options = array(
			'text_field_example' => $this->get_option( 'text_field_example', 'Default value if option not previously set' ),
		);
		//update_option( static::OPTION_NAME, $updated_options );
	}

	/**
	 * The on_theme_deactivation method is run when the blog’s theme is changed.
	 * Specifically, it fires after the theme has been switched but before the next request.
	 * You would use this method to do things when the theme is deactivated.
	 */
	function on_theme_deactivation() {
		// TODO: Implement on_theme_deactivation() method.

		//delete_option( static::OPTION_NAME );
	}
}