<?php

namespace Platonic\API;

abstract class Plugin_Settings extends Settings implements Settings_Rules
{
	/**
	 * Plugin_Settings constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		register_activation_hook( __FILE__, array( $this, 'on_plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'on_plugin_deactivation' ) );
		register_uninstall_hook(__FILE__, array( $this, 'on_plugin_uninstall' ));
	}

	function register_settings()
	{
		parent::register_settings();
	}
}