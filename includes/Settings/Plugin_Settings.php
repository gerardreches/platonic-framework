<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Plugin_Settings_Page_Rules;

abstract class Plugin_Settings extends Settings_Page implements Plugin_Settings_Page_Rules {

	/**
	 * Initialize Plugin_Settings class.
	 */
	public static function initialize(): void {
		parent::initialize();

		register_activation_hook( __FILE__, array( static::class, 'on_plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( static::class, 'on_plugin_deactivation' ) );
		register_uninstall_hook( __FILE__, array( static::class, 'on_plugin_uninstall' ) );
	}

}