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

// This constant can be used if you need to manually load the plugin by using require() or require_once()
if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_FILE' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'PLATONIC_FRAMEWORK_PLUGIN_BASENAME' ) ) {
	define( 'PLATONIC_FRAMEWORK_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

require_once( plugin_dir_path(__FILE__) . '/lib/autoload.php');
