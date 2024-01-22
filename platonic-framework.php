<?php
/**
 * Platonic Framework
 *
 * @package           Platonic\Framework
 * @author            Gerard Reches
 * @copyright         Copyright (c) 2021, Gerard Reches Urbano
 * @license           GPL v3.0
 *
 * @wordpress-plugin
 * Plugin Name:       Platonic Framework
 * Plugin URI:        https://gerardreches.com
 * Description:       WordPress Framework for the Settings API and Customizer API
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      8.0
 * Author:            Gerard Reches
 * Author URI:        https://gerardreches.com
 * Text Domain:       platonic-framework
 * License:           GPL v3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: gerardreches/platonic-framework
 * GitHub Plugin URI: https://github.com/gerardreches/platonic-framework
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//No need to proceed if Platonic already exists.
if ( class_exists( \Platonic\Framework\Settings\Settings::class ) ) {
	return;
}

/**
 * When using the Platonic Framework as a library, if any symlinks exist affecting the framework
 * then the constant PLATONIC_FRAMEWORK_PLUGIN_DIR must be defined in your plugin or theme using
 * the right path in order for the framework to enqueue its scripts successfully.
 */
if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_DIR' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_DIR', __DIR__ );
}

if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_FILE' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_BASENAME' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

function load_platonic_framework_textdomain (): void {
	load_plugin_textdomain(
		'platonic-framework',
		false,
		dirname( PLATONIC_FRAMEWORK_PLUGIN_BASENAME ) . '/languages/'
	);
}
add_action( 'plugins_loaded', 'load_platonic_framework_textdomain' );

require_once( plugin_dir_path( __FILE__ ) . '/lib/autoload.php' );
