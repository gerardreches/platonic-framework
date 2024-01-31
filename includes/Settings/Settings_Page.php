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
	final static function enqueue_admin_scripts( string $hook_suffix ): void {
		if ( self::get_page_hook_suffix() !== $hook_suffix ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( handle: 'wp-color-picker', deps: array( 'jquery' ) );
		wp_add_inline_script( 'wp-color-picker', 'jQuery(".color-picker").wpColorPicker();', 'after' );

		/**
         * Implement the admin media model for any defined file fields.
         * @see https://codex.wordpress.org/Javascript_Reference/wp.media
         *
		 * @note Script is being added inline to avoid issues when the directory is a symlink. PHP doesn't have methods to retrieve the unresolved path.
         * @see https://bugs.php.net/bug.php?id=42516
		 */
        if ( apply_filters( 'platonic_framework_add_media_script', true ) ){
		    wp_add_inline_script( 'jquery', file_get_contents( trailingslashit( dirname( __FILE__ ) ) . 'wp-media-frame.js' ), 'after' );
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
		echo "<div id='" . esc_attr( self::get_page_hook_suffix() ) . "'>";
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