<?php

namespace Platonic\Framework\Customizer\Interface;

interface CustomizerRules {

	function add_panel( string $panel_id, array $args );

	function add_section( string $section_id, array $args );

	function add_field( $settings, array $args, string $config_id );
}