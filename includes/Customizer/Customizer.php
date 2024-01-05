<?php

namespace Platonic\Framework\Customizer;

use Kirki;
use Platonic\Framework\Customizer\Interface\CustomizerRules;

class Customizer implements CustomizerRules {
	protected $option_name;

	const THEME_MOD = 'theme_mod_config';
	const OPTION = 'option_config';

	/**
	 * Settings_API constructor.
	 */
	public function __construct() {
		$this->register_config();

		add_action( 'init', array( $this, 'customize_register' ) );
	}

	protected function register_config() {
		Kirki::add_config( self::THEME_MOD, array(
			'capability'     => 'edit_theme_options',
			'option_type'    => 'theme_mod',
			'disable_output' => false,
		) );

		if ( isset( $this->option_name ) ) {
			// TO DO: Check if this config_id causes trouble when different $option_name exist through all the files.
			Kirki::add_config( self::OPTION, array(
				'capability'     => 'manage_options',
				'option_type'    => 'option',
				'option_name'    => $this->option_name,
				'disable_output' => false,
			) );
		}
	}

	/**
	 * @param string $panel_id
	 * @param array $args
	 */
	public function add_panel( string $panel_id, array $args = array() ) {
		Kirki::add_panel( $panel_id, $args );
	}

	/**
	 * @param string $section_id
	 * @param array $args
	 */
	public function add_section( string $section_id, array $args = array() ) {
		Kirki::add_section( $section_id, $args );
	}

	/**
	 * @param $settings
	 * @param array $args
	 * @param string $config_id
	 */
	public function add_field( $settings, array $args = array(), string $config_id = self::THEME_MOD ) {
		if ( ! isset( $args['setting'] ) && is_string( $settings ) ) {
			$args['setting'] = $settings;
		}
		$args['settings'] = $settings;

		Kirki::add_field( $config_id, $args );
	}

}