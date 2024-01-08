<?php

namespace Platonic\Framework\Settings\Interface;

use WP_Theme;

interface Theme_Settings_Page_Rules extends Settings_Page_Rules {

	function on_theme_activation( string $old_name, WP_Theme $old_theme ): void;

	function on_theme_deactivation( string $new_name, WP_Theme $new_theme, WP_Theme $old_theme ): void;

	function on_theme_deletion( string $stylesheet ): void;

}