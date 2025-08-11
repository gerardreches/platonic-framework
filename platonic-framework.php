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
 * Version:           1.1.0
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

// No need to proceed if Platonic already exists.
if ( class_exists( \Platonic\Framework\Settings\Settings_Page::class ) ) {
	return;
}

if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_DIR' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_DIR', __DIR__ );
}

if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_FILE' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_BASENAME' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'PLATONIC_FRAMEWORK_DEBUG_MODE' ) ) {
	define( 'PLATONIC_FRAMEWORK_DEBUG_MODE', false );
}

function platonic_framework_load_textdomain(): void {
    load_plugin_textdomain(
        'platonic-framework',
        false,
        trailingslashit( dirname( PLATONIC_FRAMEWORK_PLUGIN_BASENAME ) ) . 'languages/'
    );
}
add_action( 'init', 'platonic_framework_load_textdomain' );

require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/autoload.php' );

/**
 * Receive updates from GitHub.
 */

function platonic_framework_check_for_updates(): void {
	require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/plugin-update-checker-5.6/plugin-update-checker.php' );

	$myUpdateChecker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/gerardreches/platonic-framework',
		PLATONIC_FRAMEWORK_PLUGIN_FILE,
		'platonic-framework'
	);
}

add_action( 'admin_init', 'platonic_framework_check_for_updates' );