<?php

namespace Platonic\Api\Settings;

use Platonic\Api\Settings\Interface\SettingsRules;
use Platonic\Api\Settings\Trait\OptionsPage;
use Platonic\Api\Settings\Trait\Sanitization;
use Platonic\Api\Settings\Trait\SettingsFields;

abstract class Settings implements SettingsRules {
	use OptionsPage;
	use SettingsFields;
	use Sanitization;

	private $registered_fields;

	/**
	 * A settings group name. Should correspond to an allowed option key name.
	 * Default allowed option key names include 'general', 'discussion',
	 * 'media', 'reading', 'writing', 'misc', 'options', and 'privacy'.
	 */
	const OPTION_GROUP = null;

	/**
	 * The name of an option to sanitize and save.
	 */
	const OPTION_NAME = null;

	const MENU_POSITION = null;
	const SHOW_IN_REST = false;

	/**
	 * The slug name to refer to this menu by. Should be unique for this menu
	 * and only include lowercase alphanumeric, dashes, and underscores characters
	 * to be compatible with sanitize_key().
	 */
	const MENU_SLUG = null;

	/**
	 * Settings class constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), static::MENU_POSITION );

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// TODO: REST API compatibility. Requires schema definition.
		//add_action( 'rest_api_init', array( $this, 'register_settings' ) );

		if ( isset( $_GET['page'] ) && static::MENU_SLUG === $_GET['page'] ) {
			add_action( 'admin_enqueue_scripts', array( static::class, 'enqueue_admin_scripts' ) );
		}
	}

	/**
	 * Enqueue the necessary scripts and styles for the Settings API.
	 */
	final static function enqueue_admin_scripts() {
		wp_enqueue_script( 'jquery' );

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script( 'platonic-utils', str_replace( ABSPATH, '/', __DIR__ ) . '/utils.js' );
	}

	/**
	 * Returns all theme options
	 */
	final static function get_options() {
		return get_option( static::OPTION_NAME );
	}

	/**
	 * Returns single option
	 *
	 * @param string $id
	 * @param $default
	 *
	 * @return mixed
	 */
	final static function get_option( string $id, $default = false ) {
		// Option might not be an array.
		if ( is_null( static::OPTION_NAME ) ) {
			return get_option( $id ) ?? $default;
		}

		$options = static::get_options();

		return $options[ $id ] ?? $default;
	}

	/**
	 * Register the settings
	 */
	function register_settings() {
		// We register the settings only when necessary in order to improve performance.
		if ( empty( get_registered_settings()[ static::OPTION_NAME ] ) ) {
			register_setting(
				static::OPTION_GROUP ?? static::OPTION_NAME,
				static::OPTION_NAME,
				array(
					'type'              => 'array',
					'description'       => 'An array containing multiple options',
					'sanitize_callback' => array( $this, 'sanitize_callback' ),
					'show_in_rest'      => static::SHOW_IN_REST,
					'default'           => array()
				)
			);

			add_settings_error( static::OPTION_NAME, 'my_option_notice', "<-- THE METHOD REGISTER_SETTINGS() FROM " . get_class( $this ) . " HAS BEEN FIRED -->", 'info' );
		}
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param string $description
	 */
	final static function add_settings_section( string $id, string $title, string $description = '' ) {
		add_settings_section(
			$id,
			$title,
			function () use ( $description ) {
				echo $description;
			},
			static::class
		);
	}

	/**
	 * Add a new field to a section of a settings page.
	 *
	 * Part of the Settings API. Use this to define a settings field that will show
	 * as part of a settings section inside a settings page. The fields are shown using
	 * do_settings_fields() in do_settings-sections()
	 *
	 * The $callback argument should be the name of a function that echoes out the
	 * HTML input tags for this setting field. Use get_option() to retrieve existing
	 * values to show.
	 *
	 * @param string $id Slug-name to identify the field. Used in the 'id' attribute of tags.
	 * @param string $section Optional. The slug-name of the section of the settings page
	 *                           in which to show the box. Default 'default'.
	 * @param string $title Formatted title of the field. Shown as the label for the field
	 *                           during output.
	 * @param string $description Formatted description of the field. Shown under the field
	 *                           during output.
	 * @param string $type The type of fieldslug-name of the settings page on which to show the section
	 *                           (general, reading, writing, ...).
	 * @param array $args {
	 *     Optional. Extra arguments used when outputting the field.
	 *
	 * @type string $label_for When supplied, the setting title will be wrapped
	 *                             in a `<label>` element, its `for` attribute populated
	 *                             with this value.
	 * @type string $class CSS Class to be added to the `<tr>` element when the
	 *                             field is output.
	 * @type string $field_class CSS Class to be added to the input element when the
	 *                             field is output.
	 * @type mixed $default Default value for the option on activation.
	 * }
	 * @since 1.0
	 *
	 */
	final function add_settings_field( string $id, string $section, string $title, string $description, string $type, array $args = array() ) {
		add_settings_field(
			$id,
			$title,
			array( static::class, 'add_settings_field_callback' ),
			static::class,
			$section ?? 'default',
			array_merge(
				array(
					'label_for'   => $id,
					'description' => $description,
					'id'          => $id,
					'name'        => static::OPTION_NAME ? static::OPTION_NAME . "[{$id}]" : $id,
					'type'        => $type,
					'value'       => static::get_option( $id ),
					'default'     => null,
					'field_class' => '',
					'placeholder' => '',
					'rows'        => '10',
					'cols'        => '50',
				),
				$args
			)
		);

		// Save some information internally for convenience.
		$this->registered_fields[ $id ] = array(
			'type'    => $type,
			'value'   => static::get_option( $id ),
			'default' => $args['default'] ?? null
		);

		add_settings_error( static::OPTION_NAME, $id, $title, 'warning' );
	}

	/**
	 * Sanitize callback called on register_setting().
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	final function sanitize_callback( array $options ) {
		if ( is_null( $options ) ) {
			$options = array();
			add_settings_error( static::OPTION_NAME, static::OPTION_NAME, "Something went wrong.", 'error' );
		}
        // TODO: Temporal
		add_settings_error( static::OPTION_NAME, 'my_option_notice', "<pre>" . print_r( $options, true ) . "</pre>", 'info' );

		$message = '';
		// Sanitize each option using a different callback depending on its type.
		foreach ( $options as $key => $value ) {
			// If the current key is not in the registered fields, this callback comes from a different instance.
			if ( ! array_key_exists( $key, $this->registered_fields ) ) {
				continue;
			}

			$sanitization_callback = array( $this, 'sanitize_' . $this->registered_fields[ $key ]['type'] );
			$options[ $key ]       = static::sanitize( $value, $sanitization_callback );

			$message = $message . "{$key} sanitized with value {$options[ $key ]}<br>";
		}
		// TODO: Temporal
		add_settings_error( static::OPTION_NAME, 'my_option_notice', $message, 'success' );

		// If non-present options were saved under the same OPTION_NAME, merge them so that they don't get deleted.
		// This is essential for when using the same OPTION_NAME in different option pages.
		$saved_options = static::get_options();

		if ( is_array( $saved_options ) && is_array( $options ) ) {
			$options = array_merge( $saved_options, $options );
		}
		// TODO: Temporal
		add_settings_error( static::OPTION_NAME, 'my_option_notice', "SANITIZATION CALLBACK EXECUTED FOR " . static::class, 'success' );

		return $options;
	}

	/**
	 * Output the admin page containing the form with the fields that have been registered.
	 */
	final static function create_settings_page() {
		do_action( 'platonic_before_settings_page' );
		?>
        <div class='wrap'>
            <!-- Displays the title -->
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <!-- Displays error or updated notices -->
			<?php settings_errors(); ?>
            <!-- The form must point to options.php -->
            <form action='options.php' method='POST'>
				<?php
				// Output the necessary hidden fields : nonce, action, and option page name
				settings_fields( static::OPTION_GROUP ?? static::OPTION_NAME );
				// Loops through registered sections and fields for the page slug passed in, and display them.
				do_settings_sections( static::class );
				// Displays a submit button
				submit_button();
				?>
            </form>
        </div>
		<?php
		do_action( 'platonic_after_settings_page' );
	}
}