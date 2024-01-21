<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Settings_Rules;
use Platonic\Framework\Settings\Trait\Menu_Page_Handler;
use Platonic\Framework\Settings\Trait\Settings_Fields;
use Platonic\Framework\Settings\Trait\Sanitization;
use Platonic\Framework\Settings\Trait\Option_Lifecycle_Manager;

abstract class Settings implements Settings_Rules {

	use Menu_Page_Handler;
	use Settings_Fields;
	use Sanitization;
	use Option_Lifecycle_Manager;

	/**
	 * The slug name to refer to this menu by. Should be unique for this menu
	 * and only include lowercase alphanumeric, dashes, and underscores characters
	 * to be compatible with sanitize_key().
	 */
	const MENU_SLUG = null;
	const MENU_POSITION = null;
	const ADMIN_PAGE = null;

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

	const DISABLE_SETTINGS_ERRORS = false;

	/**
	 * Settings class constructor.
	 */
	public function __construct() {

		if ( empty( static::OPTION_NAME ) || ! is_string( static::OPTION_NAME ) ) {
			_doing_it_wrong( __METHOD__, __( "The constant OPTION_NAME has to be set as a non-empty string. Remember to use a unique name to avoid conflicts.", 'platonic-framework' ), '1.0' );
		}
		add_action( 'admin_menu', array( static::class, 'add_admin_menu' ) );

		add_action( 'admin_init', array( static::class, 'register' ) );

		// TODO: REST API compatibility. Requires schema definition.
		//add_action( 'rest_api_init', array( static::class, 'register_settings' ) );

		add_action( 'admin_enqueue_scripts', array( static::class, 'enqueue_admin_scripts' ), 10, 1 );

		/**
		 * Hook into the option's lifecycle.
		 */
		static::manage_option_lifecycle( static::OPTION_NAME );
	}

	/**
	 * Enqueue the necessary scripts and styles for the Settings API.
	 *
	 * @param string $hook_suffix
	 *
	 * @return void
	 */
	static function enqueue_admin_scripts( string $hook_suffix ): void {

		/**
		 * TODO: Load only when necessary by using $hook_suffix
		 *
		 * @note Ideally it should be done without requiring a new constant
		 */
		//if ( isset( $_GET['page'] ) && static::MENU_SLUG === $_GET['page'] ) { }
		if ( null === static::ADMIN_PAGE || static::ADMIN_PAGE === $hook_suffix ) {
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
					if ( ! defined( 'PLATONIC_FRAMEWORK_DISABLE_LOG' ) || ! PLATONIC_FRAMEWORK_DISABLE_LOG ) {
						error_log( "WARNING: Platonic Framework has been symlinked. The script utils.js might not be loading correctly. If it is not loading correctly and you are using the Platonic Framework in your theme or plugin, please define the constant PLATONIC_FRAMEWORK_PLUGIN_DIR with the correct path to the Platonic Framework. To disable this warning, use define( 'PLATONIC_FRAMEWORK_DISABLE_LOG', true ) in your functions.php or your plugin main file." );
					}
				}
			}
			wp_enqueue_script( 'platonic-framework-utils', $utils_path );
		}
	}

	/**
	 * Returns single option
	 *
	 * @param string|null $id
	 * @param mixed|false $default_value
	 *
	 * @return mixed
	 */
	final static function get_option( string $id = null, mixed $default_value = false ): mixed {

		$option = get_option( static::OPTION_NAME );

		return is_null( $id ) ? $option ?? $default_value : $option[ $id ] ?? $default_value;
	}

	final static function register_setting( string $option_name, array $args = array() ): void {

		if ( array_key_exists( $option_name, get_registered_settings() ) ) {
			add_settings_error( $option_name, "{$option_name}-warning", "Setting <em>{$option_name}</em> is being registered twice. This may cause unexpected issues. Check your error log for more details.", 'warning' );
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
			<?php if ( ! static::DISABLE_SETTINGS_ERRORS ) {
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