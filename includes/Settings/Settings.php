<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Settings_Rules;
use Platonic\Framework\Settings\Trait\Option_Lifecycle_Manager;
use Platonic\Framework\Settings\Trait\Options_Page;
use Platonic\Framework\Settings\Trait\Sanitization;
use Platonic\Framework\Settings\Trait\Settings_Fields;

abstract class Settings implements Settings_Rules {

	use Options_Page;
	use Settings_Fields;
	use Sanitization;
	use Option_Lifecycle_Manager;

	private array $registered_settings;
	private array $registered_sections;
	private array $registered_fields;

	/**
	 * The slug name to refer to this menu by. Should be unique for this menu
	 * and only include lowercase alphanumeric, dashes, and underscores characters
	 * to be compatible with sanitize_key().
	 */
	const MENU_SLUG = null;
	const MENU_POSITION = null;

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
	const DEFAULT = array();

	const SHOW_IN_REST = false;

	const SHOW_SETTINGS_ERRORS = true;

	/**
	 * Settings class constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// TODO: REST API compatibility. Requires schema definition.
		//add_action( 'rest_api_init', array( $this, 'register_settings' ) );

		if ( isset( $_GET['page'] ) && static::MENU_SLUG === $_GET['page'] ) {
			add_action( 'admin_enqueue_scripts', array( static::class, 'enqueue_admin_scripts' ) );
		}

        /**
         * Hook into the option lifecycle.
         */
		static::manage_option_lifecycle( static::OPTION_NAME );
	}

	/**
     * Enqueue the necessary scripts and styles for the Settings API.
     *
	 * @return void
	 */
	final static function enqueue_admin_scripts(): void {
		wp_enqueue_script( 'jquery' );

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		if ( str_contains( __DIR__, ABSPATH ) ) {
			// Enqueue script taking into account that Platonic Framework may be either a plugin or a library used in another plugin or theme.
			$utils_path = trailingslashit( str_replace( ABSPATH, '/', __DIR__ ) ) . 'utils.js';
		} else {
			// The plugin has been symlinked and the previous enqueue method won't resolve.
			$utils_path = plugin_dir_url( __FILE__ ) . 'utils.js';

			// When using the Platonic Framework as a library, PLATONIC_FRAMEWORK_PLUGIN_DIR must be defined in your plugin or theme using the right path.
			if ( PLATONIC_FRAMEWORK_PLUGIN_DIR !== dirname( __DIR__, 2 ) ) {
				$utils_path = trailingslashit( PLATONIC_FRAMEWORK_PLUGIN_DIR ) . 'includes/Settings/utils.js';
			} else {
				if ( ! defined( 'PLATONIC_FRAMEWORK_DISABLE_LOG' ) || false === PLATONIC_FRAMEWORK_DISABLE_LOG ) {
					error_log( "WARNING: Platonic Framework has been symlinked. The script utils.js might not be loading correctly. If it is not loading correctly and you are using the Platonic Framework in your theme or plugin, please define the constant PLATONIC_FRAMEWORK_PLUGIN_DIR with the correct path to the Platonic Framework. To disable this warning, use define( 'PLATONIC_FRAMEWORK_DISABLE_LOG', true ) in your functions.php or your plugin main file." );
				}
			}
		}
		wp_enqueue_script( 'platonic-framework-utils', $utils_path );
	}

	/**
     * Returns all theme options
     *
	 * @return mixed
	 */
	final static function get_options(): mixed {
		return get_option( static::OPTION_NAME );
	}

	/**
	 * Returns single option
	 *
	 * @param string $id
	 * @param mixed|false $default_value
	 *
	 * @return mixed
	 */
	final static function get_option( string $id, mixed $default_value = false ): mixed {
		// Option might not be an array.
		if ( is_null( static::OPTION_NAME ) ) {
			return get_option( $id ) ?? $default_value;
		}

		$options = static::get_options();

		return $options[ $id ] ?? $default_value;
	}

	/**
     * Register the settings
     *
	 * @return void
	 */
	function register_settings(): void {

		if ( array_key_exists( static::OPTION_NAME, get_registered_settings() ) ) {
			add_settings_error( static::OPTION_NAME, static::OPTION_NAME, "Setting <em>" . static::OPTION_NAME . "</em> is being registered twice. This may cause unexpected issues. Check your error log for more details.", 'warning' );
			error_log( "Class " . get_class( $this ) . " is registering a setting named " . static::OPTION_NAME . " which was already registered. The setting's arguments will be overwritten, which may cause unexpected issues. Please, consider registering a new setting instead of an existent one." );
		}

		register_setting(
			static::OPTION_GROUP ?? static::OPTION_NAME,
			static::OPTION_NAME,
			array(
				'type'              => 'array',
				'description'       => 'An array containing multiple options',
				'sanitize_callback' => array( $this, 'sanitize_callback' ),
				'show_in_rest'      => static::SHOW_IN_REST,
				'default'           => static::DEFAULT ?? array()
			)
		);

        $this->add_settings();
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @param string $description
	 *
	 * @return string
	 */
	final static function add_settings_section( string $id, string $title, string $description = '' ): string {
		add_settings_section(
			$id,
			$title,
			function () use ( $description ) {
				echo $description;
			},
			static::class
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
	 * @param string $title Formatted title for the field. Shown as the label for the field
	 *                           during output.
	 * @param string $description Formatted description for the field. Shown under the field
	 *                           during output.
	 * @param string $type The type of field.
	 * @param string $section Optional. The slug-name of the section of the settings page
	 *                           in which to show the box. Default 'default'.
	 * @param array $args {
	 *     Optional. Extra arguments used when outputting the field.
	 *
	 * @return string
	 * @since 1.0
	 */
	final function add_settings_field( string $id, string $type, string $title, string $description = '', string $section = 'default', array $args = array() ): string {
		add_settings_field(
			$id,
			$title,
			$args['callback'] ?? array( static::class, 'add_settings_field_callback' ),
			static::class,
			$section,
			array_merge(
				array(
					'label_for'   => $id,
					'description' => $description,
					'id'          => $id,
					'name'        => static::OPTION_NAME ? static::OPTION_NAME . "[{$id}]" : $id,
					'type'        => $type,
					'value'       => static::get_option( $id ),
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

		// Save some information internally for convenience.
		$this->registered_fields[ $id ] = array(
			'type'    => $type,
			'value'   => static::get_option( $id ),
			'default' => $args['default'] ?? null
		);

		return $id;
	}

	/**
	 * Sanitize callback called on register_setting().
	 *
	 * @param array|null $options
	 *
	 * @return array|null
	 */
	final function sanitize_callback( ?array $options ): ?array {

		// TODO: Temporal
		add_settings_error( static::OPTION_NAME, 'my_option_notice', "SANITIZATION CALLBACK EXECUTED FOR " . static::class, 'success' );

		if ( is_null( $options ) ) {
			$options = array();
			add_settings_error( static::OPTION_NAME, static::OPTION_NAME, "Something went wrong. $options is null.", 'error' );
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

			$message .= "{$key} sanitized with value {$options[ $key ]}<br>";
		}
		// TODO: Temporal
		add_settings_error( static::OPTION_NAME, 'my_option_notice', $message, 'success' );

		// If non-present options were saved under the same OPTION_NAME, merge them so that they don't get deleted.
		// This is essential for when using the same OPTION_NAME in different option pages.
		$saved_options = static::get_options();

		if ( is_array( $saved_options ) && is_array( $options ) ) {
			$options = array_merge( $saved_options, $options );
		}

		return $options;
	}

	/**
	 * Output the admin page containing the form with the fields that have been registered.
	 */
    static function create_settings_page(): void {
		do_action( 'platonic_before_settings_page' );
		?>
        <div class='wrap'>
            <!-- Displays the title -->
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <!-- Displays error or updated notices -->
			<?php if ( static::SHOW_SETTINGS_ERRORS ) {
				settings_errors();
			} ?>
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