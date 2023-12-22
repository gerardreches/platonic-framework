<?php

use Platonic\API\Plugin_Settings;
use Platonic\API\Plugin_Settings_Page_Rules;

/**
 * INTRODUCTION
 *
 * This is an example of how to create your own settings page
 * in a simplified way using the Platonic API.
 *
 * The Platonic API is a wrapper for the WordPress Settings API,
 * based on an Object-Oriented Programming approach.
 *
 * You would replace the name of this class with your own,
 * extend the Platonic\API\Settings class, and
 * implement the Settings_Page_Rules interface.
 *
 * This example class contains some methods that are 100% optional.
 * These methods are advanced usage examples, and they are not required
 * at all, so feel free to skip them. Don't let them overwhelm you,
 * it is really easy to create your settings page with the Platonic API.
 *
 * There are only 2 required methods: add_admin_menu() and register_settings()
 *
 */
class Plugin_Settings_Page_Example extends Plugin_Settings implements Plugin_Settings_Page_Rules
{
	protected $option_group = 'your_option_group';
	protected $option_name = 'your_option_name';

	/**
	 * Add admin menu
	 */
	public function add_admin_menu()
	{
		$this->add_options_page(
			__( 'Page Title', 'your-text-domain' ),
			__( 'Menu Title', 'your-text-domain' ),
			'menu_slug'
		);
	}

	public function register_settings()
	{
		// The parent class will register the settings in the database.
		parent::register_settings();

		// Register your sections and fields
		$this->add_settings_section(
			'first_section',
			__( 'First Section Title', 'your-text-domain' ),
			__( 'This is the description for this section', 'your-text-domain' )
		);

		$this->add_settings_field(
			'text_field_example',
			'first_section',
			__( 'Text Field Title', 'your-text-domain' ),
			__( 'Description for your text field.', 'your-text-domain' ),
			'text'
		);

		$this->add_settings_field(
			'email_field_example',
			'first_section',
			__( 'Email Field Title', 'your-text-domain' ),
			__( 'Description for your email field.', 'your-text-domain' ),
			'email'
		);

		$this->add_settings_field(
			'color_field_example',
			'first_section',
			__( 'Color Field Title', 'your-text-domain' ),
			__( 'Description for your color field.', 'your-text-domain' ),
			'color'
		);

		$this->add_settings_field(
			'checkbox_field_example',
			'first_section',
			__( 'Checkbox Field Title', 'your-text-domain' ),
			__( 'Description for your checkbox field.', 'your-text-domain' ),
			'checkbox'
		);

		$this->add_settings_section(
			'second_section',
			__( 'Second Section Title', 'your-text-domain' ),
			__( 'This is the description for this section', 'your-text-domain' )
		);

		$this->add_settings_field(
			'file_example',
			'second_section',
			__( 'File Field Title', 'your-text-domain' ),
			__( 'Description for your file selector.', 'your-text-domain' ),
			'file'
		);

		$this->add_settings_field(
			'radio_example',
			'second_section',
			__( 'Radio Buttons Title', 'your-text-domain' ),
			__( 'Description for your radio buttons.', 'your-text-domain' ),
			'radio',
			array(
				'options' => array(
					'first_value'  => __( 'Label for your first radio button', 'your-text-domain' ),
					'second_value' => __( 'Label for your second radio button', 'your-text-domain' ),
					'third_value'  => __( 'Label for your third radio button', 'your-text-domain' )
				)
			)
		);

		$this->add_settings_field(
			'select_example',
			'second_section',
			__( 'Select Title', 'your-text-domain' ),
			__( 'Description for your select dropdown.', 'your-text-domain' ),
			'select',
			array(
				'options' => array(
					'first_value'  => __( 'Label for your first option', 'your-text-domain' ),
					'second_value' => __( 'Label for your second option', 'your-text-domain' ),
					'third_value'  => __( 'Label for your third option', 'your-text-domain' )
				)
			)
		);
	}

	/**
	 * The on_plugin_activation method is run when you activate your plugin.
	 * You would use this to provide a function to set up your plugin —
	 * for example, creating some default settings in the options table.
	 */
	function on_plugin_activation()
	{
		// TODO: Implement on_plugin_deactivation() method.

		$updated_options = array(
			'text_field_example' => $this->get_option( 'text_field_example', 'Default value if option not previously set' ),
		);
		//update_option( $this->option_name, $updated_options );
	}

	/**
	 * The on_plugin_deactivation method is run when you deactivate your plugin.
	 * You would use this to provide a function that clears any
	 * temporary data stored by your plugin.
	 */
	function on_plugin_deactivation()
	{
		// TODO: Implement on_plugin_deactivation() method.
	}

	/**
	 * The on_plugin_uninstall method is run after your plugin is deleted using the WordPress Admin.
	 * You would use this to delete all data created by your plugin,
	 * such as any options that were added to the options table.
	 */
	function on_plugin_uninstall()
	{
		// TODO: Implement on_plugin_uninstall() method.
	}
}