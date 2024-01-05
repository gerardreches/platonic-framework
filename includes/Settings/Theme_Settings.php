<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Theme_Settings_Page_Rules;

abstract class Theme_Settings extends Settings implements Theme_Settings_Page_Rules {

	/**
	 * Theme_Settings constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'switch_theme', array( $this, 'on_theme_deactivation' ) );
		add_action( 'after_switch_theme', array( $this, 'on_theme_activation' ) );
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