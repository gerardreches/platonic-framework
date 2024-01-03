<?php
/**
 * Platonic SettingsInterface API
 *
 * @package           Platonic\API
 * @author            Gerard Reches
 * @copyright         Copyright (c) 2021, Gerard Reches Urbano
 * @license           GPL v3.0
 *
 * @wordpress-plugin
 * Plugin Name:       Platonic SettingsInterface API
 * Plugin URI:        https://gerardreches.com
 * Description:       WordPress SettingsInterface API Framework
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      8.0
 * Author:            Gerard Reches
 * Author URI:        https://gerardreches.com
 * Text Domain:       platonic-settings-api
 * License:           GPL v3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: gerardreches/platonic-settings-api
 * GitHub Plugin URI: https://github.com/gerardreches/platonic-settings-api
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//No need to proceed if Platonic already exists.
if ( class_exists( \Platonic\Api\Settings\SettingsInterface::class ) ) {
	return;
}

// This constant can be used if you need to manually load the plugin by using require() or require_once()
if ( ! defined( 'PLATONIC_SETTINGS_API_PLUGIN_FILE' ) ) {
	define( 'PLATONIC_SETTINGS_API_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'PLATONIC_SETTINGS_API_PLUGIN_BASENAME' ) ) {
	define( 'PLATONIC_SETTINGS_API_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

require_once( plugin_dir_path(__FILE__) . '/lib/autoload.php');
