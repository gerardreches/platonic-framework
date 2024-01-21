<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Plugin_Settings_Page_Rules;

abstract class Plugin_Settings extends Settings_Page implements Plugin_Settings_Page_Rules {

	/**
	 * Plugin_Settings constructor.
	 */
	public function __construct() {
		parent::__construct();

		register_activation_hook( __FILE__, array( $this, 'on_plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'on_plugin_deactivation' ) );
		register_uninstall_hook( __FILE__, array( static::class, 'on_plugin_uninstall' ) );
	}

}