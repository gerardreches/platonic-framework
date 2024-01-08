<?php

namespace Platonic\Framework\Settings\Example;

use Platonic\Framework\Settings\Interface\Plugin_Settings_Page_Rules;
use Platonic\Framework\Settings\Plugin_Settings;

/**
 * INTRODUCTION
 *
 * This is an example of how to create your own settings page
 * in a simplified way using the Platonic Framework.
 *
 * The Platonic Framework is a wrapper for the WordPress Settings API,
 * based on an Object-Oriented Programming approach.
 *
 * You would replace the name of this class with your own,
 * extend the Platonic\Framework\Plugin_Settings class, and
 * implement the Plugin_Settings_Page_Rules interface.
 *
 * This example class contains some methods that are 100% optional.
 * These methods are advanced usage examples, and they are not required
 * at all, so feel free to skip them. Don't let them overwhelm you,
 * it is really easy to create your settings page with the Platonic Framework.
 *
 * There are only 2 required methods: add_admin_menu() and register_settings()
 *
 */
class Plugin_Settings_Page_Example extends Plugin_Settings implements Plugin_Settings_Page_Rules {

	const OPTION_GROUP = 'your_option_group';
	const OPTION_NAME = 'your_option_name';

	/**
	 * Add admin menu
	 */
	public function add_admin_menu(): void {
		self::add_options_page(
			__( 'Page Title', 'your_text_domain' ),
			__( 'Menu Title', 'your_text_domain' )
		);
	}

	public function register_settings(): void {

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
			'number_field_example',
			'first_section',
			__( 'Number Field Title', 'your_text_domain' ),
			__( 'Description for your number field.', 'your_text_domain' ),
			'number'
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
	 * The on_plugin_activation method is executed when you activate your plugin.
	 * You would use this to provide a function to set up your plugin —
	 * for example, creating default settings in the options table.
	 */
	function on_plugin_activation(): void {
		// TODO: Implement on_plugin_activation() method.
		// add_option( self::OPTION_NAME, self::DEFAULT );
	}

	/**
	 * The on_plugin_deactivation method is executed when you deactivate your plugin.
	 * You would use this to provide a function that clears any
	 * temporary data stored by your plugin.
	 */
	function on_plugin_deactivation(): void {
		// TODO: Implement on_plugin_deactivation() method.
		// delete_transient( $transient );
	}

	/**
	 * The on_plugin_uninstall method is executed after your plugin is deleted through the WordPress Admin.
	 * You would use this to delete all data created by your plugin,
	 * such as any options that were added to the options table.
	 */
	static function on_plugin_uninstall(): void {
		// TODO: Implement on_plugin_uninstall() method.
		// delete_option( self::OPTION_NAME ) || error_log( "Option " . self::OPTION_NAME . " couldn't be deleted on plugin uninstallation." );
	}
}