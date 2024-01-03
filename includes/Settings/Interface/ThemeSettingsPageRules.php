<?php

namespace Platonic\Api\Settings\Interface;

interface ThemeSettingsPageRules extends SettingsPageRules {
	function on_theme_activation( $new_theme );

	function on_theme_deactivation();
}