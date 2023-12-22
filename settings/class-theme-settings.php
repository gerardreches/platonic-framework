<?php

namespace Platonic\API;

abstract class Theme_Settings extends Settings implements Theme_Settings_Page_Rules
{
	/**
	 * Theme_Settings constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		add_action( 'switch_theme', array( $this, 'on_theme_deactivation' ) );
		add_action( 'after_switch_theme', array( $this, 'on_theme_activation' ) );
	}

	function register_settings()
	{
		parent::register_settings();
	}
}