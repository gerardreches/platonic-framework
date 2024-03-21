<?php

namespace Platonic\Framework\Settings;

use Platonic\Framework\Settings\Interface\Theme_Settings_Page_Rules;

abstract class Theme_Settings extends Settings_Page implements Theme_Settings_Page_Rules {

	/**
	 * Initialize Theme_Settings class.
	 */
	public static function initialize(): void {
		parent::initialize();

		add_action( 'after_switch_theme', array( static::class, 'on_theme_activation' ), 10, 2 );
		add_action( 'switch_theme', array( static::class, 'on_theme_deactivation' ), 10, 3 );
		add_action( 'delete_theme', array( static::class, 'on_theme_deletion' ), 10, 1 );
	}

}