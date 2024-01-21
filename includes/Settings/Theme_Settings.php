<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Theme_Settings_Page_Rules;

abstract class Theme_Settings extends Settings_Page implements Theme_Settings_Page_Rules {

	/**
	 * Theme_Settings constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'after_switch_theme', array( $this, 'on_theme_activation' ), 10, 2 );
		add_action( 'switch_theme', array( $this, 'on_theme_deactivation' ), 10, 3 );
		add_action( 'delete_theme', array( $this, 'on_theme_deletion' ), 10, 1 );
	}

}