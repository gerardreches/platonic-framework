<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Settings_Page_Rules;
use Platonic\Framework\Settings\Trait\Menu_Page_Handler;

abstract class Settings_Page extends Settings implements Settings_Page_Rules {

	use Menu_Page_Handler;

	/**
	 * The slug name to refer to this menu by. Should be unique for this menu
	 * and only include lowercase alphanumeric, dashes, and underscores characters
	 * to be compatible with sanitize_key().
	 */
	const MENU_SLUG = null;
	const MENU_POSITION = null;
	const DISABLE_SETTINGS_ERRORS = false;

	/**
	 * Settings class constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_menu', array( static::class, 'add_admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( static::class, 'enqueue_admin_scripts' ), 10, 1 );
	}

	/**
	 * Enqueue the necessary scripts and styles for the Settings API.
	 *
	 * @param string $hook_suffix
	 *
	 * @return void
	 */
	static function enqueue_admin_scripts( string $hook_suffix ): void {
		if ( self::$page_hook_suffix[ static::class ] === $hook_suffix ) {
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

	final static function settings_fields(): void {
		settings_fields( static::OPTION_GROUP ?? static::OPTION_NAME );
	}

	final static function do_settings_sections(): void {
		do_settings_sections( static::class );
	}

	final static function do_settings_fields( string $section ): void {
		do_settings_fields( static::class, $section );
	}

	final static function settings_errors( bool $sanitize = false, bool $hide_on_update = false ): void {
		if ( static::DISABLE_SETTINGS_ERRORS ) {
			// TODO: Remove all debugging settings errors
			settings_errors( static::OPTION_NAME, $sanitize, $hide_on_update );
		} else {
			settings_errors( static::OPTION_NAME, $sanitize, $hide_on_update );
		}
	}

	/**
	 * Output the admin page containing the form with the fields that have been registered.
	 */
	final static function create_settings_page(): void {
		echo "<div id='" . esc_attr( self::$page_hook_suffix[ static::class ] ) . "'>";
		do_action( 'platonic_framework_before_settings_page' );
		static::create_form();
		do_action( 'platonic_framework_after_settings_page' );
		echo '</div>';
	}

	static function create_form(): void {
		?>
        <div class='wrap'>
            <!-- Displays the title -->
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <!-- Displays error or updated notices -->
			<?php self::settings_errors(); ?>
            <!-- The form must point to options.php -->
            <form action='options.php' method='POST'>
				<?php
				// Output the necessary hidden fields : nonce, action, and option page name
				self::settings_fields();
				// Loops through registered sections and fields for the page slug passed in, and display them.
				self::do_settings_sections();
				// Displays a submit button
				submit_button( text: null, type: 'primary', name: 'submit', wrap: true, other_attributes: null );
				?>
            </form>
        </div>
		<?php
	}
}