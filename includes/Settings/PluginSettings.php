<?php

namespace Platonic\Api\Settings;

use Platonic\Api\Settings\Interface\PluginSettingsPageRules;

abstract class PluginSettings extends Settings implements PluginSettingsPageRules {

	/**
	 * PluginSettings constructor.
	 */
	public function __construct() {
		parent::__construct();

		register_activation_hook( __FILE__, array( $this, 'on_plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'on_plugin_deactivation' ) );
		register_uninstall_hook( __FILE__, array( $this, 'on_plugin_uninstall' ) );
	}

	/**
	 * We redefine this function so that you can extend it in your own class in case you need to.
	 *
	 * @return void
	 */
	function register_settings() {
		parent::register_settings();
	}
}