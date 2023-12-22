<?php
/**
 * Platonic Settings API
 *
 * @package           Platonic\API
 * @author            Gerard Reches
 * @copyright         Copyright (c) 2021, Gerard Reches Urbano
 * @license           GPL v3.0
 *
 * @wordpress-plugin
 * Plugin Name:       Platonic Settings API
 * Plugin URI:        https://gerardreches.com
 * Description:       WordPress Settings API Framework
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Gerard Reches
 * Author URI:        https://gerardreches.com
 * Text Domain:       platonic-settings-api
 * License:           GPL v3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: gerardreches/platonic-settings-api
 * GitHub Plugin URI: https://github.com/gerardreches/platonic-settings-api
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
{
	exit;
}

// No need to proceed if Platonic already exists.
if ( class_exists( \Platonic\API\Settings::class ) )
{
	return;
}

require_once 'settings/interface-settings-rules.php';
require_once 'settings/interface-settings-page-rules.php';
require_once 'settings/interface-theme-settings-page-rules.php';
require_once 'settings/interface-plugin-settings-page-rules.php';

require_once 'settings/trait-settings-menus.php';
require_once 'settings/trait-settings-fields.php';
require_once 'settings/trait-settings-sanitization.php';

require_once 'settings/class-settings.php';
require_once 'settings/class-theme-settings.php';
require_once 'settings/class-plugin-settings.php';

